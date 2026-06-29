<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CourseAccountCreatedMail;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\User;
use App\Services\MidtransGateway;
use App\Services\WhatsAppGateway;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MidtransController extends Controller
{
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

        $package = Package::active()->where('slug', $validated['package_type'])->first();

        if (! $package) {
            return response()->json([
                'message' => 'Paket yang dipilih tidak tersedia.',
            ], 404);
        }

        $existingRegistration = Registration::where('email', $validated['email'])->latest('id')->first();

        if ($existingRegistration?->status === 'active') {
            return response()->json([
                'message' => 'Email ini sudah memiliki akun aktif. Silakan cek email/WhatsApp atau login.',
            ], 409);
        }

        if ($existingRegistration?->status === 'pending') {
            $existingOrder = $existingRegistration->user?->orders()->latest('id')->first();

            if ($existingOrder && $existingOrder->status === 'pending' && filled($existingOrder->payment?->snap_token)) {
                return response()->json([
                    'message' => 'Masih ada pembayaran pending. Silakan lanjutkan pembayaran sebelumnya.',
                    'registration' => $this->registrationPayload($existingOrder),
                    'token' => $existingOrder->payment->snap_token,
                    'redirect_url' => null,
                ]);
            }

            $this->purgeRegistration($existingRegistration);
        }

        if ($existingRegistration?->status === 'cancelled') {
            $this->purgeRegistration($existingRegistration);
        }

        try {
            [$registration, $user, $order] = DB::transaction(function () use ($validated, $package) {
                $registration = Registration::create([
                    'full_name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['whatsapp'],
                    'status' => 'pending',
                ]);

                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'whatsapp' => $validated['whatsapp'],
                    'role' => 'client',
                    'is_active' => false,
                    'registration_id' => $registration->id,
                ]);

                $order = Order::create([
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'order_number' => $this->generateOrderNumber(),
                    'amount' => $package->price,
                    'status' => 'pending',
                ]);

                return [$registration, $user, $order];
            });
        } catch (\Throwable $exception) {
            Log::error('Gagal membuat pendaftaran kursus.', ['exception' => $exception]);

            return response()->json([
                'message' => 'Gagal membuat pendaftaran kursus.',
            ], 500);
        }

        try {
            $transaction = $this->midtransGateway->createSnapToken($order->refresh()->load('user'), $package);

            Payment::create([
                'order_id' => $order->id,
                'provider' => 'midtrans',
                'snap_token' => $transaction['token'] ?? null,
                'status' => 'pending',
                'payload' => $transaction,
            ]);

            return response()->json([
                'message' => 'Pendaftaran berhasil dibuat. Lanjutkan pembayaran.',
                'registration' => $this->registrationPayload($order->refresh()->load(['user', 'package', 'payment'])),
                'token' => $transaction['token'] ?? null,
                'redirect_url' => $transaction['redirect_url'] ?? null,
            ], 201);
        } catch (RequestException $exception) {
            $response = $exception->response?->json();
            $message = $response['status_message']
                ?? ($response['error_messages'][0] ?? $exception->getMessage());

            return response()->json([
                'message' => 'Pendaftaran tersimpan, tetapi Midtrans menolak transaksi: ' . $message,
                'registration' => $this->registrationPayload($order->load(['user', 'package', 'payment'])),
            ], 502);
        } catch (ConnectionException $exception) {
            return response()->json([
                'message' => 'Pendaftaran tersimpan, tetapi tidak dapat terhubung ke Midtrans. Silakan coba beberapa saat lagi.',
                'registration' => $this->registrationPayload($order->load(['user', 'package', 'payment'])),
            ], 502);
        } catch (\Throwable $exception) {
            Log::error('Gagal membuat Snap Token Midtrans.', [
                'order_id' => $order->id,
                'exception' => $exception,
            ]);

            return response()->json([
                'message' => 'Pendaftaran tersimpan, tetapi gagal membuat Snap Token Midtrans.',
                'registration' => $this->registrationPayload($order->load(['user', 'package', 'payment'])),
            ], 500);
        }
    }

    public function checkStatus(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['user', 'package', 'payment'])
            ->first();

        if (! $order) {
            return response()->json([
                'message' => 'Data pendaftaran tidak ditemukan. Hapus data lokal browser.',
                'should_clear_local_storage' => true,
            ], 404);
        }

        $paymentStatus = $this->mapStatus($order);

        if ($paymentStatus === 'pending') {
            return response()->json([
                'message' => 'Transaksi masih menunggu pembayaran.',
                'status' => 'pending',
                'should_show_reminder' => true,
                'should_clear_local_storage' => false,
                'registration' => $this->registrationPayload($order),
            ]);
        }

        return response()->json([
            'message' => 'Transaksi sudah selesai atau kedaluwarsa. Hapus data lokal browser.',
            'status' => $paymentStatus,
            'should_show_reminder' => false,
            'should_clear_local_storage' => true,
            'registration' => $this->registrationPayload($order),
        ]);
    }

    public function syncPaymentStatus(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['user', 'package', 'payment'])
            ->first();

        if (! $order) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }

        try {
            $status = $this->midtransGateway->getTransactionStatus($orderNumber);

            Log::info('Midtrans Sync Status Payload:', (array) $status);
        } catch (RequestException $exception) {
            $response = $exception->response?->json();
            $message = $response['status_message'] ?? ($response['error_messages'][0] ?? $exception->getMessage());
            return response()->json(['message' => 'Gagal mengecek status Midtrans: ' . $message], 502);
        } catch (ConnectionException $exception) {
            return response()->json(['message' => 'Tidak dapat terhubung ke Midtrans.'], 502);
        }

        return $this->applyMidtransStatus($order, $status);
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

        $order = Order::where('order_number', $payload['order_id'])
            ->with(['user', 'package', 'payment'])
            ->first();

        if (! $order) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }

        return $this->applyMidtransStatus(
            $order,
            [
                'transaction_status' => $payload['transaction_status'],
                'fraud_status' => $payload['fraud_status'] ?? null,
            ],
            isSignatureVerified: true
        );
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
            'transaction_id' => ['nullable', 'string'],
            'payment_type' => ['nullable', 'string'],
        ]);

        if (! $this->midtransGateway->isValidSignature($payload)) {
            return response()->json(['message' => 'Signature Midtrans tidak valid.'], 403);
        }

        $order = Order::where('order_number', $payload['order_id'])
            ->with(['user', 'package', 'payment'])
            ->first();

        if (! $order) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }

        return $this->applyMidtransStatus(
            $order,
            [
                'transaction_status' => $payload['transaction_status'],
                'fraud_status' => $payload['fraud_status'] ?? null,
                'transaction_id' => $payload['transaction_id'] ?? null,
                'payment_type' => $payload['payment_type'] ?? null,
            ],
            isSignatureVerified: true
        );
    }

    private function applyMidtransStatus(Order $order, array $status, bool $isSignatureVerified = false)
    {
        $transactionStatus = $status['transaction_status'] ?? null;
        $fraudStatus = $status['fraud_status'] ?? null;

        if (in_array($transactionStatus, ['settlement', 'capture'], true) && $fraudStatus !== 'deny') {
            return $this->markPaymentSuccess($order, $status);
        }

        if (in_array($transactionStatus, ['expire', 'cancel', 'deny', 'failure'], true)) {
            return $this->markPaymentExpired($order, $status);
        }

        return response()->json([
            'message' => 'Transaksi belum sukses atau masih pending.',
            'payment_status' => $this->mapStatus($order),
            'midtrans_status' => $transactionStatus,
        ]);
    }

    private function markPaymentSuccess(Order $order, array $status = [])
    {
        if ($order->status === 'paid') {
            return response()->json(['message' => 'Pembayaran sudah pernah diproses.']);
        }

        $plainPassword = Str::upper(Str::random(8));
        $username = $this->generateUniqueUsername();

        DB::transaction(function () use ($order, $status, $plainPassword, $username) {
            $order->user->update([
                'username' => $username,
                'password' => $plainPassword,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $order->payment?->update([
                'status' => 'settlement',
                'paid_at' => now(),
                'transaction_id' => $status['transaction_id'] ?? $order->payment?->transaction_id,
                'payment_type' => $status['payment_type'] ?? $order->payment?->payment_type,
                'payload' => array_merge((array) $order->payment?->payload, $status),
            ]);

            $order->user->registration?->update([
                'status' => 'active',
                'account_created_at' => now(),
            ]);

            Enrollment::firstOrCreate(
                [
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                ],
                [
                    'package_id' => $order->package_id,
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays($order->package->duration_days)->toDateString(),
                    'status' => 'active',
                ]
            );
        });

        $user = $order->user->refresh();

        $mailSent = $this->sendAccountEmail($order->refresh(), $plainPassword);
        $waSent = $this->whatsAppGateway->sendAccountReceipt(
            $user->whatsapp,
            $user->name,
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

    private function markPaymentExpired(Order $order, array $status = [])
    {
        DB::transaction(function () use ($order, $status) {
            $order->update(['status' => 'expired']);

            $order->payment?->update([
                'status' => $status['transaction_status'] ?? 'expire',
                'payload' => array_merge((array) $order->payment?->payload, $status),
            ]);

            $order->user->registration?->update(['status' => 'cancelled']);
        });

        return response()->json([
            'message' => 'Transaksi sudah kedaluwarsa atau dibatalkan.',
            'payment_status' => 'expired',
        ]);
    }

    private function sendAccountEmail(Order $order, string $plainPassword): bool
    {
        try {
            Mail::to($order->user->email)->send(new CourseAccountCreatedMail($order, $plainPassword));

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Gagal mengirim email akun kursus.', [
                'order_id' => $order->id,
                'email' => $order->user->email,
                'exception' => $exception,
            ]);

            return false;
        }
    }

    private function purgeRegistration(Registration $registration): void
    {
        DB::transaction(function () use ($registration) {
            $user = $registration->user;

            if ($user) {
                $user->delete();
            }

            $registration->delete();
        });
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'NA-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    private function generateUniqueUsername(): string
    {
        do {
            $username = 'nihon_' . Str::lower(Str::random(5));
        } while (User::where('username', $username)->exists());

        return $username;
    }

    private function mapStatus(Order $order): string
    {
        return match ($order->status) {
            'paid' => 'success',
            'expired', 'failed', 'cancelled' => 'expired',
            default => 'pending',
        };
    }

    private function registrationPayload(Order $order): array
    {
        return [
            'order_id' => $order->order_number,
            'name' => $order->user?->name,
            'email' => $order->user?->email,
            'whatsapp' => $order->user?->whatsapp,
            'package_type' => $order->package?->slug,
            'package_name' => $order->package?->name,
            'payment_status' => $this->mapStatus($order),
            'payment_token' => $order->payment?->snap_token,
        ];
    }
}
