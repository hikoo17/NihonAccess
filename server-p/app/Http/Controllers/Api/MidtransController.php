<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CourseAccountCreatedMail;
use App\Models\User;
use App\Services\MidtransGateway;
use App\Services\WhatsAppGateway;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MidtransController extends Controller
{
    private array $packageCatalog = [
        'basic' => ['title' => 'Paket Basic Online', 'price' => 99000, 'duration' => '1 bulan'],
        'premium' => ['title' => 'Paket Premium Online', 'price' => 199000, 'duration' => '3 bulan'],
        'private' => ['title' => 'Paket Private Online', 'price' => 499000, 'duration' => '6 bulan'],
        'reguler' => ['title' => 'Paket Basic On-Site', 'price' => 350000, 'duration' => '1 bulan'],
        'intensive' => ['title' => 'Paket Premium On-Site', 'price' => 599000, 'duration' => '1 bulan intensif'],
        'corporate' => ['title' => 'Paket Private Corporate', 'price' => 750000, 'duration' => 'Custom'],
    ];

    public function __construct(
        private MidtransGateway $midtransGateway,
        private WhatsAppGateway $whatsAppGateway,
    ) {}

    public function registerCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email:rfc,dns', 'max:150'],
            'whatsapp' => ['required', 'regex:/^(\+62|62|0)8[0-9]{8,13}$/'],
            'package_type' => ['required', 'string', 'in:basic,premium,private,reguler,intensive,corporate'],
        ], [
            'whatsapp.regex' => 'Nomor WhatsApp harus memakai format Indonesia yang valid, contoh 081234567890.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data pendaftaran belum lengkap atau tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (! $this->midtransGateway->hasRequiredKeys()) {
            return response()->json([
                'message' => 'MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY wajib diisi.',
            ], 500);
        }

        $validated = $validator->validated();
        $validated['email'] = Str::lower($validated['email']);
        $package = $this->packageCatalog[$validated['package_type']];

        $existingRegistration = User::where('email', $validated['email'])->latest('id')->first();

        if ($existingRegistration?->payment_status === 'pending' && filled($existingRegistration->payment_token)) {
            $existingPackage = $this->packageCatalog[$existingRegistration->package_type] ?? $package;

            return response()->json([
                'message' => 'Masih ada pembayaran pending. Silakan lanjutkan pembayaran sebelumnya.',
                'registration' => $this->registrationPayload($existingRegistration, $existingPackage),
                'token' => $existingRegistration->payment_token,
                'redirect_url' => null,
            ]);
        }

        if ($existingRegistration?->payment_status === 'success') {
            return response()->json([
                'message' => 'Email ini sudah memiliki akun aktif. Silakan cek email/WhatsApp atau login.',
            ], 409);
        }

        if ($existingRegistration?->payment_status === 'expired') {
            $existingRegistration->delete();
        }

        try {
            $registration = DB::transaction(fn() => User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'whatsapp' => $validated['whatsapp'],
                'package_type' => $validated['package_type'],
                'payment_status' => 'pending',
                'payment_token' => null,
                'username' => null,
                'password' => null,
            ]));
        } catch (\Throwable $exception) {
            Log::error('Gagal membuat pendaftaran kursus.', ['exception' => $exception]);

            return response()->json([
                'message' => 'Gagal membuat pendaftaran kursus.',
            ], 500);
        }

        try {
            $transaction = $this->midtransGateway->createSnapToken($registration, $package);
            $registration->update(['payment_token' => $transaction['token'] ?? null]);

            return response()->json([
                'message' => 'Pendaftaran berhasil dibuat. Lanjutkan pembayaran.',
                'registration' => $this->registrationPayload($registration->refresh(), $package),
                'token' => $transaction['token'] ?? null,
                'redirect_url' => $transaction['redirect_url'] ?? null,
            ], 201);
        } catch (RequestException $exception) {
            $response = $exception->response?->json();
            $message = $response['status_message']
                ?? ($response['error_messages'][0] ?? $exception->getMessage());

            return response()->json([
                'message' => 'Pendaftaran tersimpan, tetapi Midtrans menolak transaksi: ' . $message,
                'registration' => $this->registrationPayload($registration, $package),
            ], 502);
        } catch (ConnectionException $exception) {
            return response()->json([
                'message' => 'Pendaftaran tersimpan, tetapi tidak dapat terhubung ke Midtrans. Silakan coba beberapa saat lagi.',
                'registration' => $this->registrationPayload($registration, $package),
            ], 502);
        } catch (\Throwable $exception) {
            Log::error('Gagal membuat Snap Token Midtrans.', [
                'registration_id' => $registration->id,
                'exception' => $exception,
            ]);

            return response()->json([
                'message' => 'Pendaftaran tersimpan, tetapi gagal membuat Snap Token Midtrans.',
                'registration' => $this->registrationPayload($registration, $package),
            ], 500);
        }
    }

    public function checkStatus(string $orderId)
    {
        $registration = User::find($orderId);

        if (! $registration) {
            return response()->json([
                'message' => 'Data pendaftaran tidak ditemukan. Hapus data lokal browser.',
                'should_clear_local_storage' => true,
            ], 404);
        }

        $package = $this->packageCatalog[$registration->package_type] ?? null;

        if ($registration->payment_status === 'pending') {
            return response()->json([
                'message' => 'Transaksi masih menunggu pembayaran.',
                'status' => 'pending',
                'should_show_reminder' => true,
                'should_clear_local_storage' => false,
                'registration' => $this->registrationPayload($registration, $package),
            ]);
        }

        return response()->json([
            'message' => 'Transaksi sudah selesai atau kedaluwarsa. Hapus data lokal browser.',
            'status' => $registration->payment_status,
            'should_show_reminder' => false,
            'should_clear_local_storage' => true,
            'registration' => $this->registrationPayload($registration, $package),
        ]);
    }

    public function syncPaymentStatus(string $orderId)
    {
        $registration = User::find($orderId);

        if (! $registration) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }

        try {
            $status = $this->midtransGateway->getTransactionStatus($orderId);

            // 🛠️ TAMBAHKAN LOG INI untuk debugging di storage/logs/laravel.log
            \Log::info('Midtrans Sync Status Payload:', (array) $status);
        } catch (RequestException $exception) {
            $response = $exception->response?->json();
            $message = $response['status_message'] ?? ($response['error_messages'][0] ?? $exception->getMessage());
            return response()->json(['message' => 'Gagal mengecek status Midtrans: ' . $message], 502);
        } catch (ConnectionException $exception) {
            return response()->json(['message' => 'Tidak dapat terhubung ke Midtrans.'], 502);
        }

        $transactionStatus = $status['transaction_status'] ?? null;
        $fraudStatus = $status['fraud_status'] ?? null;

        // Pastikan kondisi pengecekan string dari Midtrans sudah tepat
        if (in_array($transactionStatus, ['settlement', 'capture'], true) && $fraudStatus !== 'deny') {
            return $this->markPaymentSuccess($registration);
        }

        if (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
            $registration->update(['payment_status' => 'expired']);
            return response()->json([
                'message' => 'Transaksi sudah kedaluwarsa atau dibatalkan.',
                'payment_status' => 'expired',
            ]);
        }

        return response()->json([
            'message' => 'Transaksi belum sukses atau masih pending.',
            'payment_status' => $registration->payment_status,
            'midtrans_status' => $transactionStatus,
        ]);
    }

    public function confirmSnapPayment(Request $request)
    {
        $payload = $request->validate([
            'order_id' => ['required', 'string'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required', 'string'],
            'signature_key' => ['required', 'string'],
            'transaction_status' => ['required', 'string'],
            'fraud_status' => ['nullable', 'string'],
        ]);

        if (! $this->midtransGateway->isValidSignature($payload)) {
            return response()->json(['message' => 'Signature hasil Snap tidak valid.'], 403);
        }

        $registration = User::find($payload['order_id']);

        if (! $registration) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }

        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (in_array($transactionStatus, ['settlement', 'capture'], true) && $fraudStatus !== 'deny') {
            return $this->markPaymentSuccess($registration);
        }

        return response()->json([
            'message' => 'Hasil Snap belum menunjukkan pembayaran sukses.',
            'payment_status' => $registration->payment_status,
            'midtrans_status' => $transactionStatus,
        ]);
    }

    public function callback(Request $request)
    {
        $payload = $request->validate([
            'order_id' => ['required', 'string'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required', 'string'],
            'signature_key' => ['required', 'string'],
            'transaction_status' => ['required', 'string'],
            'fraud_status' => ['nullable', 'string'],
        ]);

        if (! $this->midtransGateway->isValidSignature($payload)) {
            return response()->json(['message' => 'Signature Midtrans tidak valid.'], 403);
        }

        $registration = User::find($payload['order_id']);

        if (! $registration) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }

        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (in_array($transactionStatus, ['settlement', 'capture'], true) && $fraudStatus !== 'deny') {
            return $this->markPaymentSuccess($registration);
        }

        if (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
            $registration->update(['payment_status' => 'expired']);

            return response()->json(['message' => 'Status pembayaran diubah menjadi expired.']);
        }

        return response()->json(['message' => 'Callback diterima tanpa perubahan status.']);
    }

    private function markPaymentSuccess(User $registration)
    {
        if ($registration->payment_status === 'success') {
            return response()->json(['message' => 'Pembayaran sudah pernah diproses.']);
        }

        $plainPassword = Str::upper(Str::random(8));
        $username = $this->generateUniqueUsername();

        $registration->update([
            'payment_status' => 'success',
            'username' => $username,
            'password' => Hash::make($plainPassword),
        ]);

        $mailSent = $this->sendAccountEmail($registration->refresh(), $plainPassword);
        $waSent = $this->whatsAppGateway->sendAccountReceipt(
            $registration->whatsapp,
            $registration->name,
            $username,
            $plainPassword
        );

        return response()->json([
            'message' => 'Pembayaran sukses dan akun kursus sudah dibuat.',
            'payment_status' => 'success',
            'account_created' => true,
            'delivery' => [
                'email_sent' => $mailSent,
                'whatsapp_sent' => $waSent,
            ],
        ]);
    }

    private function sendAccountEmail(User $registration, string $plainPassword): bool
    {
        try {
            Mail::to($registration->email)->send(new CourseAccountCreatedMail($registration, $plainPassword));

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Gagal mengirim email akun kursus.', [
                'registration_id' => $registration->id,
                'email' => $registration->email,
                'exception' => $exception,
            ]);

            return false;
        }
    }

    private function generateUniqueUsername(): string
    {
        do {
            $username = 'nihon_' . Str::lower(Str::random(5));
        } while (User::where('username', $username)->exists());

        return $username;
    }

    private function registrationPayload(User $registration, ?array $package): array
    {
        return [
            'order_id' => (string) $registration->id,
            'name' => $registration->name,
            'email' => $registration->email,
            'whatsapp' => $registration->whatsapp,
            'package_type' => $registration->package_type,
            'package_name' => $package['title'] ?? $registration->package_type,
            'payment_status' => $registration->payment_status,
            'payment_token' => $registration->payment_token,
        ];
    }
}
