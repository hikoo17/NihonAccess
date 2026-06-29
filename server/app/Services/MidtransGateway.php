<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Package;
use Illuminate\Support\Facades\Http;

class MidtransGateway
{
    public function createSnapToken(Order $order, Package $package): array
    {
        $user = $order->user;

        return Http::withBasicAuth($this->serverKey(), '')
            ->acceptJson()
            ->asJson()
            ->post($this->snapUrl(), [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $package->price,
                ],
                'item_details' => [[
                    'id' => $package->slug,
                    'price' => (int) $package->price,
                    'quantity' => 1,
                    'name' => $package->name,
                    'brand' => 'NihonAccess',
                    'category' => 'Course',
                    'merchant_name' => 'NihonAccess',
                ]],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->whatsapp,
                ],
                'callbacks' => [
                    'finish' => config('services.midtrans.finish_url'),
                ],
                'custom_field1' => $package->duration_days . ' hari',
                'custom_field2' => $package->slug,
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

    public function getTransactionStatus(string $orderNumber): array
    {
        $response = Http::withBasicAuth($this->serverKey(), '')
            ->acceptJson()
            ->get($this->statusUrl($orderNumber))
            ->throw();

        logger()->info('Midtrans transaction status synced.', [
            'order_number' => $orderNumber,
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

    private function statusUrl(string $orderNumber): string
    {
        $baseUrl = config('services.midtrans.is_production')
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        return $baseUrl.'/v2/'.$orderNumber.'/status';
    }

    private function serverKey(): ?string
    {
        return config('services.midtrans.server_key');
    }
}
