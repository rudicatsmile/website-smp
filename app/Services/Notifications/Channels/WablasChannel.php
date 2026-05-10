<?php

declare(strict_types=1);

namespace App\Services\Notifications\Channels;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WablasChannel
{
    /**
     * Send a WhatsApp text message via Wablas API.
     *
     * Wablas v2 endpoint typically: POST {base_url}/send-message
     * Headers: Authorization: {token}.{secret}
     * Body: phone, message
     */
    public function send(string $phone, string $message): ChannelResult
    {
        $driver = config('notifications.wablas.driver', 'http');

        if ($driver === 'log') {
            Log::info('[Wablas:LOG-DRIVER] WA message', [
                'phone'   => $phone,
                'message' => $message,
            ]);

            return ChannelResult::ok(['driver' => 'log']);
        }

        $baseUrl = rtrim((string) config('services.wablas.base_url'), '/');
        $token   = (string) config('services.wablas.token');
        $secret  = (string) config('services.wablas.secret');
        $timeout = (int) config('services.wablas.timeout', 15);

        if (! $baseUrl || ! $token) {
            return ChannelResult::fail('Wablas belum dikonfigurasi (WABLAS_BASE_URL/WABLAS_TOKEN kosong).');
        }

        $authHeader = $secret !== '' ? "{$token}.{$secret}" : $token;

        try {
            $response = Http::timeout($timeout)
                ->withHeaders([
                    'Authorization' => $authHeader,
                ])
                ->asForm()
                ->post($baseUrl . '/send-message', [
                    'phone'   => $phone,
                    'message' => $message,
                ]);
        } catch (\Throwable $e) {
            return ChannelResult::fail('HTTP error: ' . $e->getMessage());
        }

        $payload = [];
        try {
            $payload = $response->json() ?: ['raw' => $response->body()];
        } catch (\Throwable) {
            $payload = ['raw' => $response->body()];
        }

        if (! $response->successful()) {
            return ChannelResult::fail(
                'Wablas HTTP ' . $response->status() . ': ' . ($payload['message'] ?? $response->body()),
                $payload,
            );
        }

        // Wablas returns { status: true|false, message: ..., data: ... }
        $statusOk = (bool) ($payload['status'] ?? false);
        if (! $statusOk) {
            return ChannelResult::fail(
                'Wablas: ' . ($payload['message'] ?? 'unknown error'),
                $payload,
            );
        }

        return ChannelResult::ok($payload);
    }
}
