<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class MidtransGateway
{
    public function createSnapToken(User $registration, array $package): array
    {
        return Http::withBasicAuth($this->serverKey(), '')
            ->acceptJson()
            ->asJson()
            ->post($this->snapUrl(), [
                'transaction_details' => [
                    'order_id' => (string) $registration->order_id,
                    'gross_amount' => $package['price'],
                ],
                'item_details' => [[
                    'id' => $registration->package_type,
                    'price' => $package['price'],
                    'quantity' => 1,
                    'name' => $package['title'],
                    'brand' => 'NihonAccess',
                    'category' => 'Course',
                    'merchant_name' => 'NihonAccess',
                ]],
                'customer_details' => [
                    'first_name' => $registration->name,
                    'email' => $registration->email,
                    'phone' => $registration->whatsapp,
                ],
                'callbacks' => [
                    'finish' => config('services.midtrans.finish_url'),
                ],
                'custom_field1' => $package['duration'],
                'custom_field2' => $registration->package_type,
            ])
            ->throw()
            ->json();
    }

    public function isValidSignature(array $payload): bool
    {
        $signature = hash(
            'sha512',
            $payload['order_id'].$payload['status_code'].$payload['gross_amount'].$this->serverKey()
        );

        return hash_equals($signature, $payload['signature_key']);
    }

    public function getTransactionStatus(string $orderId): array
    {
        $response = Http::withBasicAuth($this->serverKey(), '')
            ->acceptJson()
            ->get($this->statusUrl($orderId))
            ->throw();

        logger()->info('Midtrans transaction status synced.', [
            'order_id' => $orderId,
            'response' => $response->json(),
        ]);

        return $response->json();
    }

    public function hasRequiredKeys(): bool
    {
        return filled($this->serverKey()) && filled(config('services.midtrans.client_key'));
    }

    private function snapUrl(): string
    {
        return config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    private function statusUrl(string $orderId): string
    {
        $baseUrl = config('services.midtrans.is_production')
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        return $baseUrl.'/v2/'.$orderId.'/status';
    }

    private function serverKey(): ?string
    {
        return config('services.midtrans.server_key');
    }
}
