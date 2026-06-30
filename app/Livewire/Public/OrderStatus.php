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

        $stored      = $this->order->mpesa_phone ?? '';
        $this->phone = str_starts_with($stored, '254') ? substr($stored, 3) : $stored;
        $this->expireIfDue();
        $this->maybeTriggerStatusCheck();
    }

    public function checkStatus(): void
    {
        $this->order->refresh();
        $this->expireIfDue();
        $this->maybeTriggerStatusCheck();
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

        $normalizedPhone = MpesaService::normalizePhone($this->phone);

        $this->order->update(['mpesa_phone' => $normalizedPhone]);

        try {
            $response = app(MpesaService::class)->initiateStkPush($this->order, $normalizedPhone);
        } catch (\Throwable $e) {
            $this->addError('phone', 'M-Pesa is unreachable. Please try again shortly.');
            return;
        }

        if (! isset($response['CheckoutRequestID'])) {
            $reason = $response['errorMessage'] ?? $response['ResultDesc'] ?? 'M-Pesa did not accept the request.';
            $this->addError('phone', $reason);
            return;
        }

        CheckPaymentStatusJob::dispatch($this->order->id, attempt: 1)
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
            && $this->order->mpesa_checkout_request_id === null
            && $this->order->expires_at?->isPast()
        ) {
            $this->order->markExpired();
        }
    }

    private function maybeTriggerStatusCheck(): void
    {
        // Only relevant for PROCESSING orders with an STK push in flight
        if (
            $this->order->payment_status !== PaymentStatus::PROCESSING
            || ! $this->order->mpesa_checkout_request_id
        ) {
            return;
        }

        $lastQuery = $this->order->last_status_query_at;

        // Give the user at least 30 s to enter their PIN before the first query
        if ($this->order->updated_at->diffInSeconds(now()) < 30) {
            return;
        }

        // Don't dispatch more than once every 30 s to avoid hammering the API
        if ($lastQuery && $lastQuery->diffInSeconds(now()) < 30) {
            return;
        }

        CheckPaymentStatusJob::dispatch(
            $this->order->id,
            attempt: max(1, ($this->order->status_query_attempts ?? 0) + 1)
        );
    }
}
