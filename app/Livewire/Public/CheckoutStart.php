<?php

namespace App\Livewire\Public;

use App\Jobs\CheckPaymentStatusJob;
use App\Models\Event;
use App\Services\MpesaService;
use App\Services\OrderPricingService;
use App\Services\PromoCodeEngine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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

    public ?string $errorMessage = null;

    public ?string $referrerCode = null;

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

        if (session('referrer_event_id') === $this->event->id) {
            $this->referrerCode = session('referrer_code');
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

        $this->promoResult = PromoCodeEngine::apply($this->event, $code, $summary['subtotal']);
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

        $this->errorMessage = null;

        $order = null;

        try {
            $normalizedPhone = MpesaService::normalizePhone($this->phone);

            $checkoutItems = [];
            foreach ($this->items as $typeId => $qty) {
                $checkoutItems[] = ['ticket_type_id' => (int) $typeId, 'quantity' => (int) $qty];
            }

            $checkout = app(\App\Services\CheckoutService::class);

            $user = Auth::check()
                ? Auth::user()
                : $checkout->resolveGuestUser($this->email, $this->name);

            $order = $checkout->checkout($user, $this->event, $checkoutItems, $this->promoResult, $this->referrerCode);

            $guestToken = null;

            if (! Auth::check()) {
                $guestToken = (string) Str::uuid();
                $order->update(['guest_token' => $guestToken]);
            }

            // Store phone before the API call so SMS works on the reconciliation path
            $order->update(['mpesa_phone' => $normalizedPhone]);

            $response = app(MpesaService::class)->initiateStkPush($order, $normalizedPhone);

            if (! isset($response['CheckoutRequestID'])) {
                $reason = $response['errorMessage'] ?? $response['ResultDesc'] ?? 'M-Pesa did not accept the request. Please try again.';
                $order->markFailed($reason);
                $this->errorMessage = $reason;
                return;
            }

            CheckPaymentStatusJob::dispatch($order->id, attempt: 1)
                ->delay(now()->addSeconds(30));

            session()->forget(['checkout_items', 'checkout_event_id']);

            $url = route('orders.status', $order->uuid);
            if ($guestToken) {
                $url .= '?token=' . $guestToken;
            }

            $this->redirect($url, navigate: false);
        } catch (\Throwable $e) {
            $order?->markFailed('Payment request failed. Please try again.');
            $this->errorMessage = $e->getMessage();
        }
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
        ]);
    }
}
