<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppGateway
{
    public function sendAccountReceipt(string $phone, string $name, string $username, string $plainPassword): bool
    {
        $token = config('services.whatsapp.token');
        $endpoint = config('services.whatsapp.endpoint');

        if (! filled($token) || ! filled($endpoint)) {
            Log::warning('WhatsApp gateway belum dikonfigurasi.');

            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(15)
                ->withHeaders(['Authorization' => $token])
                ->post($endpoint, [
                    'target' => $this->normalizePhone($phone),
                    'message' => $this->message($name, $username, $plainPassword),
                ]);

            if ($response->failed()) {
                Log::warning('WhatsApp gateway gagal mengirim pesan.', [
                    'phone' => $phone,
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (ConnectionException $exception) {
            Log::warning('WhatsApp gateway tidak dapat dihubungi.', [
                'phone' => $phone,
                'exception' => $exception,
            ]);

            return false;
        }
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($phone, '0')) {
            return '62'.substr($phone, 1);
        }

        return $phone;
    }

    private function message(string $name, string $username, string $plainPassword): string
    {
        return "Halo {$name}, pembayaran kursus Nihon Access Anda sudah berhasil.\n\n".
            "Detail akun belajar:\n".
            "Username: {$username}\n".
            "Password: {$plainPassword}\n\n".
            "Silakan login dan ubah password setelah masuk. Simpan pesan ini dengan aman.";
    }
}
