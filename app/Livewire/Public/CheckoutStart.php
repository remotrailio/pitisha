<?php

namespace App\Livewire\Public;

use App\Enums\PaymentStatus;
use App\Models\Event;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\MpesaService;
use App\Services\OrderPricingService;
use App\Services\PromoCodeEngine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Checkout'])]
class CheckoutStart extends Component
{
    public Event $event;

    public array $items = [];

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $promoCodeInput = '';

    public ?array $promoResult = null;

    // idle | processing | polling | success | failed
    public string $state = 'idle';

    public ?string $errorMessage = null;

    public ?int $orderId = null;

    public ?string $guestToken = null;

    public int $pollCount = 0;

    private const MAX_POLLS = 20; // ~60 seconds at 3s intervals

    public function mount(string $slug): void
    {
        $this->event = Event::with('ticketTypes')->where('slug', $slug)->firstOrFail();

        $this->items = session('checkout_items', []);

        if (empty($this->items) || session('checkout_event_id') !== $this->event->id) {
            $this->redirect(route('events.show', $this->event->slug));
            return;
        }

        if (Auth::check()) {
            $this->name  = Auth::user()->name;
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone ?? '';
        }
    }

    public function applyPromo(): void
    {
        $code = trim($this->promoCodeInput);

        if ($code === '') {
            $this->promoResult = null;
            return;
        }

        $ticketTypes = $this->event->ticketTypes()->get();
        $summary     = app(OrderPricingService::class)
            ->buildOrderSummary($this->event, $ticketTypes, $this->items);

        $result = PromoCodeEngine::apply($this->event, $code, $summary['subtotal']);

        $this->promoResult = $result;
    }

    public function removePromo(): void
    {
        $this->promoResult    = null;
        $this->promoCodeInput = '';
    }

    public function pay(): void
    {
        $rules = ['phone' => ['required', 'string', 'min:9']];

        if (! Auth::check()) {
            $rules['name']  = ['required', 'string', 'min:2'];
            $rules['email'] = ['required', 'email'];
        }

        $this->validate($rules);

        $this->state        = 'processing';
        $this->errorMessage = null;

        try {
            $normalizedPhone = MpesaService::normalizePhone($this->phone);

            $checkoutItems = [];
            foreach ($this->items as $typeId => $qty) {
                $checkoutItems[] = ['ticket_type_id' => (int) $typeId, 'quantity' => (int) $qty];
            }

            /** @var CheckoutService $checkout */
            $checkout = app(CheckoutService::class);

            $user = Auth::check()
                ? Auth::user()
                : $checkout->resolveGuestUser($this->email, $this->name);

            $order = $checkout->checkout($user, $this->event, $checkoutItems, $this->promoResult);

            if (! Auth::check()) {
                $this->guestToken = (string) \Illuminate\Support\Str::uuid();
                $order->update(['guest_token' => $this->guestToken]);
            }

            $this->orderId = $order->id;

            /** @var MpesaService $mpesa */
            $mpesa    = app(MpesaService::class);
            $response = $mpesa->initiateStkPush($order, $normalizedPhone);

            $order->update(['mpesa_phone' => $normalizedPhone]);

            if (! isset($response['CheckoutRequestID'])) {
                $this->state        = 'failed';
                $this->errorMessage = $response['errorMessage']
                    ?? $response['ResultDesc']
                    ?? 'M-Pesa did not accept the request. Please try again.';
                return;
            }

            $this->state     = 'polling';
            $this->pollCount = 0;

            session()->forget(['checkout_items', 'checkout_event_id']);
        } catch (\Throwable $e) {
            $this->state        = 'failed';
            $this->errorMessage = $e->getMessage();
        }
    }

    public function poll(): void
    {
        if ($this->state !== 'polling' || ! $this->orderId) {
            return;
        }

        $this->pollCount++;

        $order = Order::find($this->orderId);

        if (! $order) {
            $this->state        = 'failed';
            $this->errorMessage = 'Order not found.';
            return;
        }

        if ($order->payment_status === PaymentStatus::PAID) {
            $this->state = 'success';
            return;
        }

        if ($order->payment_status === PaymentStatus::FAILED) {
            $this->state        = 'failed';
            $this->errorMessage = 'Payment was not completed. Please try again.';
            return;
        }

        if ($this->pollCount >= self::MAX_POLLS) {
            $this->state        = 'failed';
            $this->errorMessage = 'Payment confirmation timed out. If your money was deducted, contact support with your order number: ' . $order->order_number;
        }
    }

    public function retry(): void
    {
        $this->state        = 'idle';
        $this->errorMessage = null;
        $this->orderId      = null;
        $this->pollCount    = 0;
        $this->promoResult  = null;
        $this->promoCodeInput = '';
    }

    public function render()
    {
        $ticketTypes    = $this->event->ticketTypes()->get();
        $discountAmount = ($this->promoResult && $this->promoResult['success'])
            ? $this->promoResult['discount_amount']
            : 0;

        $summary = app(OrderPricingService::class)
            ->buildOrderSummary($this->event, $ticketTypes, $this->items, $discountAmount);

        return view('livewire.public.checkout-start', [
            'itemSummary'    => $summary['lines'],
            'total'          => $summary['total'],
            'subtotal'       => $summary['subtotal'],
            'fee'            => $summary['fee'],
            'currency'       => $summary['currency'],
            'discountAmount' => $discountAmount,
            'order'          => $this->orderId ? Order::find($this->orderId) : null,
        ]);
    }
}
