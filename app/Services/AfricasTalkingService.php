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

        $isSandbox = config('africastalking.username') === 'sandbox';
        $senderId  = config('africastalking.sender_id');

        $request = Http::withHeaders([
            'Accept' => 'application/json',
            'apiKey' => config('africastalking.api_key'),
        ]);

        if ($isSandbox) {
            $payload = [
                'username' => config('africastalking.username'),
                'message'  => $message,
                'to'       => implode(',', $numbers),
            ];
            if ($senderId) {
                $payload['from'] = $senderId;
            }
            $response = $request->asForm()->post(config('africastalking.sms_endpoint'), $payload);
        } else {
            $payload = [
                'username'     => config('africastalking.username'),
                'message'      => $message,
                'phoneNumbers' => $numbers,
            ];
            if ($senderId) {
                $payload['senderId'] = $senderId;
            }
            $response = $request->post(config('africastalking.sms_endpoint'), $payload);
        }

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
