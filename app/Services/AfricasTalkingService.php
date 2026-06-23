<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AfricasTalkingService
{
    public function sendSms(array $phoneNumbers, string $message): bool
    {
        $numbers = array_values(array_filter($phoneNumbers));

        if (empty($numbers)) {
            Log::warning('AfricasTalking: no phone numbers provided, skipping');
            return false;
        }

        $payload = [
            'username'     => config('africastalking.username'),
            'message'      => $message,
            'phoneNumbers' => $numbers,
        ];

        $senderId = config('africastalking.sender_id');
        if ($senderId) {
            $payload['senderId'] = $senderId;
        }

        $response = Http::withHeaders([
            'Accept'   => 'application/json',
            'apiKey'   => config('africastalking.api_key'),
        ])->post(config('africastalking.base_url') . '/version1/messaging/bulk', $payload);

        if ($response->failed()) {
            Log::error('AfricasTalking: SMS send failed', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'numbers' => $numbers,
            ]);
            return false;
        }

        Log::info('AfricasTalking: SMS sent', [
            'numbers' => $numbers,
            'response' => $response->json(),
        ]);

        return true;
    }
}
