<?php

namespace App\Livewire\Public;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\PaymentStatus;
use App\Jobs\CheckPaymentStatusJob;
use App\Models\Order;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Order Status'])]
class OrderStatus extends Component
{
    public Order $order;

    public ?string $guestToken = null;

    public string $phone = '';

    public bool $showRetryForm = false;

    public function mount(string $uuid): void
    {
        $this->guestToken = request('token');

        $query = Order::with(['event', 'items.ticketType'])
            ->where('uuid', $uuid);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('guest_token', $this->guestToken);
        }

        $this->order = $query->firstOrFail();

        $stored      = $this->order->latestPayment?->phone ?? '';
        $this->phone = str_starts_with($stored, '254') ? substr($stored, 3) : $stored;
        $this->expireIfDue();
        $this->queryStatusIfDue();   // synchronous on page load — resolves without queue
    }

    public function checkStatus(): void
    {
        $this->order->refresh();
        $this->expireIfDue();
        $this->maybeTriggerStatusCheck(); // async job for background polling
    }

    public function requestPaymentPrompt(): void
    {
        $this->validate(['phone' => ['required', 'string', 'min:9']]);

        if ($this->order->isPaid()) {
            return;
        }

        if ($this->order->expires_at?->isPast()) {
            $this->addError('phone', 'This order has expired. Please place a new order.');
            return;
        }

        // Don't send a new push while one is still active — M-Pesa rejects it as "system busy"
        $latestPayment = $this->order->payments()->latest()->first();
        if (
            $latestPayment
            && $latestPayment->status === PaymentStatus::PROCESSING
            && $latestPayment->initiated_at?->diffInSeconds(now()) < 60
        ) {
            $this->addError('phone', 'A payment prompt was already sent to your phone. Please check your M-Pesa or wait a moment before retrying.');
            return;
        }

        $normalizedPhone = MpesaService::normalizePhone($this->phone);

        try {
            $response = app(MpesaService::class)->initiateStkPush($this->order, $normalizedPhone);
        } catch (\Throwable) {
            $this->addError('phone', 'M-Pesa is unreachable. Please try again shortly.');
            return;
        }

        if (! isset($response['CheckoutRequestID'])) {
            $reason = $response['errorMessage'] ?? $response['ResultDesc'] ?? 'M-Pesa did not accept the request.';
            $this->addError('phone', $reason);
            return;
        }

        $payment = $this->order->payments()->latest()->firstOrFail();

        CheckPaymentStatusJob::dispatch($payment->id, attempt: 1)
            ->delay(now()->addSeconds(30));

        $this->showRetryForm = false;
        $this->order->refresh();
    }

    public function render()
    {
        $this->order->refresh();

        $status    = $this->order->payment_status;
        $isExpired = $this->order->status === OrderStatusEnum::EXPIRED;

        return view('livewire.public.order-status', [
            'status'          => $status,
            'isPaid'          => $this->order->isPaid(),
            'isExpired'       => $isExpired,
            'canRetry'        => ! $this->order->isPaid()
                                 && ! $isExpired
                                 && in_array($status, [PaymentStatus::UNPAID, PaymentStatus::PROCESSING, PaymentStatus::FAILED]),
            'shouldPoll'      => in_array($status, [PaymentStatus::UNPAID, PaymentStatus::PROCESSING, PaymentStatus::UNKNOWN]),
            'pollInterval'    => $status === PaymentStatus::UNKNOWN ? 30000 : 4000,
            'guestToken'      => $this->guestToken,
        ]);
    }

    private function expireIfDue(): void
    {
        if (
            $this->order->status === OrderStatusEnum::PENDING
            && $this->order->payment_status === PaymentStatus::UNPAID
            && $this->order->expires_at?->isPast()
        ) {
            $this->order->markExpired();
        }
    }

    private function queryStatusIfDue(): void
    {
        if ($this->order->payment_status !== PaymentStatus::PROCESSING) {
            return;
        }

        $payment = $this->order->payments()->latest()->first();

        if (! $payment?->checkout_request_id) {
            return;
        }

        // Give the user at least 30 s to enter their PIN
        if ($payment->created_at->diffInSeconds(now()) < 30) {
            return;
        }

        // Don't re-query if one ran in the last 30 s
        if ($payment->last_status_query_at?->diffInSeconds(now()) < 30) {
            return;
        }

        $result = app(MpesaService::class)->queryStatus($payment);

        $payment->update([
            'status_query_attempts' => ($payment->status_query_attempts ?? 0) + 1,
            'last_status_query_at'  => now(),
        ]);

        if ($result['paid']) {
            $this->order->markPaid(
                paymentReference: $result['receipt'] ?? 'RECONCILED-' . $this->order->order_number,
                mpesaReceipt: $result['receipt'],
                payment: $payment,
            );
            \App\Jobs\GenerateTicketsJob::dispatch($this->order->id);
            $this->order->refresh();
            return;
        }

        if ($result['resolved'] && ! $result['paid']) {
            $this->order->markFailed($result['result_desc'] ?? 'Payment declined', payment: $payment);
            $this->order->refresh();
        }
    }

    private function maybeTriggerStatusCheck(): void
    {
        if ($this->order->payment_status !== PaymentStatus::PROCESSING) {
            return;
        }

        $payment = $this->order->payments()->latest()->first();

        if (! $payment?->checkout_request_id) {
            return;
        }

        // Give the user at least 30 s to enter their PIN before the first query
        if ($payment->created_at->diffInSeconds(now()) < 30) {
            return;
        }

        // Don't dispatch more than once every 30 s to avoid hammering the API
        if ($payment->last_status_query_at?->diffInSeconds(now()) < 30) {
            return;
        }

        CheckPaymentStatusJob::dispatch(
            $payment->id,
            attempt: max(1, ($payment->status_query_attempts ?? 0) + 1)
        );
    }
}
