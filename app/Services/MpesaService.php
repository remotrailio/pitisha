<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\MpesaShortcode;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MpesaService
{
    public function getAccessToken(): string
    {
        return cache()->remember('mpesa_access_token', 3500, function () {
            $response = Http::withBasicAuth(
                config('mpesa.consumer_key'),
                config('mpesa.consumer_secret')
            )->get(config('mpesa.base_url') . '/oauth/v1/generate', [
                'grant_type' => 'client_credentials',
            ]);

            if (! $response->successful() || ! $response->json('access_token')) {
                Log::error('M-Pesa token fetch failed', ['body' => $response->body()]);
                throw new RuntimeException('Could not obtain M-Pesa access token.');
            }

            return $response->json('access_token');
        });
    }

    public function initiateStkPush(Order $order, string $phone): array
    {
        $shortcode = MpesaShortcode::active()->firstOr(
            fn () => (object) ['id' => null, 'shortcode' => config('mpesa.shortcode'), 'passkey' => config('mpesa.passkey')]
        );

        $token     = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode($shortcode->shortcode . $shortcode->passkey . $timestamp);

        $payload = [
            'BusinessShortCode' => $shortcode->shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => config('mpesa.env') === 'production'
                ? (string) (int) ceil((float) $order->total)
                : '1',
            'PartyA'            => $phone,
            'PartyB'            => $shortcode->shortcode,
            'PhoneNumber'       => $phone,
            'CallBackURL'       => config('mpesa.callback_url'),
            'AccountReference'  => $order->order_number,
            'TransactionDesc'   => 'Ticket Payment – ' . $order->event->title,
        ];

        Log::info('M-Pesa STK push request', [
            'order'       => $order->order_number,
            'phone'       => $phone,
            'amount_sent' => $payload['Amount'],
            'order_total' => (int) ceil((float) $order->total),
            'sandbox'     => config('mpesa.env') !== 'production',
        ]);

        try {
            $response = Http::withToken($token)
                ->asJson()
                ->acceptJson()
                ->timeout(15)
                ->post(config('mpesa.base_url') . '/mpesa/stkpush/v1/processrequest', $payload);
        } catch (ConnectionException $e) {
            Log::error('M-Pesa STK push connection error', ['error' => $e->getMessage()]);
            throw new RuntimeException('M-Pesa API unreachable. Please try again.');
        }

        $data = $response->json();

        Log::info('M-Pesa STK push response', ['order' => $order->order_number, 'response' => $data]);

        if (isset($data['CheckoutRequestID'])) {
            $order->payments()->create(array_filter([
                'mpesa_shortcode_id'  => $shortcode->id ?? null,
                'status'              => PaymentStatus::PROCESSING,
                'provider'            => 'mpesa',
                'amount'              => $order->total,
                'currency'            => $order->currency,
                'method'              => 'stk_push',
                'phone'               => $phone,
                'checkout_request_id' => $data['CheckoutRequestID'],
                'merchant_request_id' => $data['MerchantRequestID'] ?? null,
                'response'            => $data,
                'initiated_at'        => now(),
            ], fn ($v) => $v !== null));

            $order->update(['payment_status' => PaymentStatus::PROCESSING]);
        } else {
            Log::error('M-Pesa STK push did not return CheckoutRequestID', [
                'order'    => $order->order_number,
                'response' => $data,
            ]);
        }

        return $data;
    }

    /**
     * Query M-Pesa for the current status of an STK push transaction.
     *
     * Normalised return shape:
     *   resolved    bool   – true when we have a definitive answer (success or failure)
     *   paid        bool   – true only when ResultCode === 0
     *   result_code int|null
     *   result_desc string|null
     *   receipt     string|null  – MpesaReceiptNumber on success
     *   raw         array  – original response
     */
    public function queryStatus(Payment $payment): array
    {
        $shortcode = $payment->shortcode
            ?? MpesaShortcode::active()->firstOr(
                fn () => (object) ['shortcode' => config('mpesa.shortcode'), 'passkey' => config('mpesa.passkey')]
            );

        $token     = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode($shortcode->shortcode . $shortcode->passkey . $timestamp);

        $response = Http::withToken($token)
            ->asJson()
            ->acceptJson()
            ->timeout(15)
            ->post(config('mpesa.base_url') . '/mpesa/stkpushquery/v1/query', [
                'BusinessShortCode' => $shortcode->shortcode,
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'CheckoutRequestID' => $payment->checkout_request_id,
            ]);

        $data = $response->json() ?? [];

        Log::info('M-Pesa status query', [
            'order'    => $payment->order->order_number,
            'payment'  => $payment->id,
            'response' => $data,
        ]);

        // Safaricom returns ResultCode as a string in query responses
        $resultCode = isset($data['ResultCode']) ? (int) $data['ResultCode'] : null;

        // "The transaction is being processed" comes back as an HTTP error body
        // with an errorCode key and no ResultCode — treat as still-pending
        $stillPending = ($resultCode === null && isset($data['errorCode']));

        if ($resultCode === 0) {
            // Extract receipt from CallbackMetadata if present
            $metadata = collect($data['CallbackMetadata']['Item'] ?? [])
                ->keyBy('Name')
                ->map(fn ($item) => $item['Value'] ?? null);

            return [
                'resolved'    => true,
                'paid'        => true,
                'result_code' => 0,
                'result_desc' => $data['ResultDesc'] ?? null,
                'receipt'     => $metadata->get('MpesaReceiptNumber') ? (string) $metadata->get('MpesaReceiptNumber') : null,
                'raw'         => $data,
            ];
        }

        if ($stillPending) {
            return [
                'resolved'    => false,
                'paid'        => false,
                'result_code' => null,
                'result_desc' => $data['errorMessage'] ?? 'Transaction still being processed',
                'receipt'     => null,
                'raw'         => $data,
            ];
        }

        // Non-zero ResultCode = definitive failure
        return [
            'resolved'    => $resultCode !== null,
            'paid'        => false,
            'result_code' => $resultCode,
            'result_desc' => $data['ResultDesc'] ?? null,
            'receipt'     => null,
            'raw'         => $data,
        ];
    }

    public static function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '254' . substr($phone, 1);
        }

        if (str_starts_with($phone, '+')) {
            return ltrim($phone, '+');
        }

        if (! str_starts_with($phone, '254')) {
            return '254' . $phone;
        }

        return $phone;
    }
}
