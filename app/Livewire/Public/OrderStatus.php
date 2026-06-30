<?php

namespace App\Livewire\Public;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\PaymentStatus;
use App\Jobs\InitiateStkPushJob;
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
    }

    public function checkStatus(): void
    {
        $this->order->refresh();
        $this->expireIfDue();
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

        InitiateStkPushJob::dispatch($this->order->id, $normalizedPhone);

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
}
