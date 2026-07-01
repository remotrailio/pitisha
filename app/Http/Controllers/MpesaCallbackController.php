<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Jobs\GenerateTicketsJob;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PromoCode;
use App\Services\PromoCodeEngine;
use App\Services\ReferralEngine;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MpesaCallbackController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $payload = $request->json()->all();

        Log::info('M-Pesa callback received', ['payload' => $payload]);

        // ── A. Parse payload ────────────────────────────────────────────────
        $callback = $payload['Body']['stkCallback'] ?? null;

        if (! $callback) {
            Log::warning('M-Pesa callback: missing Body.stkCallback');
            return response('', 200);
        }

        $checkoutRequestId = $callback['CheckoutRequestID'] ?? null;
        $resultCode        = $callback['ResultCode'] ?? -1;
        $resultDesc        = $callback['ResultDesc'] ?? null;

        if (! $checkoutRequestId) {
            Log::warning('M-Pesa callback: missing CheckoutRequestID');
            return response('', 200);
        }

        // ── B. Find payment (and its order) ────────────────────────────────
        $payment = Payment::where('checkout_request_id', $checkoutRequestId)
            ->with('order')
            ->first();

        if (! $payment) {
            Log::error('M-Pesa callback: payment not found', [
                'checkout_request_id' => $checkoutRequestId,
            ]);
            return response('', 200);
        }

        $order = $payment->order;

        // ── C. Idempotency ──────────────────────────────────────────────────
        if ($order->isPaid()) {
            Log::info('M-Pesa callback: order already paid — skipping', [
                'order' => $order->order_number,
            ]);
            return response('', 200);
        }

        Log::info('M-Pesa callback: processing', [
            'order'          => $order->order_number,
            'payment_status' => $order->payment_status->value,
            'result_code'    => $resultCode,
        ]);

        // ── D. Validate ResultCode ──────────────────────────────────────────
        if ($resultCode !== 0) {
            Log::warning('M-Pesa callback: payment failed', [
                'order'       => $order->order_number,
                'result_code' => $resultCode,
                'result_desc' => $resultDesc,
            ]);

            $payment->update([
                'status'              => PaymentStatus::FAILED,
                'response'            => $payload,
                'failure_reason'      => $resultDesc,
                'callback_received_at' => now(),
            ]);

            $order->update(['payment_status' => PaymentStatus::FAILED]);

            return response('', 200);
        }

        // ── E. Extract metadata ─────────────────────────────────────────────
        $metadata = collect($callback['CallbackMetadata']['Item'] ?? [])
            ->keyBy('Name')
            ->map(fn ($item) => $item['Value'] ?? null);

        $mpesaReceiptNumber = $metadata->get('MpesaReceiptNumber');
        $amount             = (float) ($metadata->get('Amount') ?? 0);

        Log::info('M-Pesa callback: payment validated', [
            'order'   => $order->order_number,
            'receipt' => $mpesaReceiptNumber,
            'amount'  => $amount,
        ]);

        // ── F. Validate amount (production only) ────────────────────────────
        if (config('mpesa.env') === 'production') {
            $expected = (float) ceil((float) $order->total);

            if ($amount < $expected) {
                Log::error('M-Pesa callback: amount mismatch', [
                    'order'    => $order->order_number,
                    'expected' => $expected,
                    'received' => $amount,
                ]);

                $order->update([
                    'payment_status' => PaymentStatus::FAILED,
                    'mpesa_response' => $payload,
                ]);

                return response('', 200);
            }
        }

        // ── G. Mark order paid ──────────────────────────────────────────────
        $order->markPaid(
            paymentReference: $mpesaReceiptNumber,
            mpesaReceipt: $mpesaReceiptNumber,
            callbackPayload: $payload,
            payment: $payment,
        );

        Log::info('M-Pesa callback: order marked paid — dispatching OrderPaid event', [
            'order'   => $order->order_number,
            'receipt' => $mpesaReceiptNumber,
        ]);

        // ── H. Consume promo code if one was applied ────────────────────────
        if ($order->discount_code) {
            $promo = PromoCode::where('event_id', $order->event_id)
                ->where('code', $order->discount_code)
                ->first();

            if ($promo) {
                PromoCodeEngine::consume($promo);
            }
        }

        // ── I. Record referral if order came through a referral link ────────
        ReferralEngine::record($order);

        // ── J. Dispatch job — ticket generation runs asynchronously ────────
        GenerateTicketsJob::dispatch($order->id);

        return response('', 200);
    }
}
