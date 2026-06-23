<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\AfricasTalkingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SendTicketsSmsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;

    public int $backoff = 15;

    public function __construct(public readonly int $orderId) {}

    public function handle(AfricasTalkingService $sms): void
    {
        $order = Order::with(['tickets', 'event', 'user'])->findOrFail($this->orderId);

        $phone = $order->mpesa_phone;

        if (! $phone) {
            Log::warning('SendTicketsSmsJob: no phone on order, skipping', [
                'order' => $order->order_number,
            ]);
            return;
        }

        $event   = $order->event;
        $tickets = $order->tickets;

        if ($tickets->isEmpty()) {
            Log::warning('SendTicketsSmsJob: no tickets yet, skipping', [
                'order' => $order->order_number,
            ]);
            return;
        }

        $eventName = $event->title;
        $eventDate = $event->start_at->format('d M Y');
        $count     = $tickets->count();

        if (! $order->guest_token) {
            $order->update(['guest_token' => (string) Str::uuid()]);
        }

        $confirmationUrl = route('orders.confirmation', $order->uuid)
            . '?token=' . $order->guest_token;

        $message = "✅ " . ($count === 1 ? 'Ticket' : "{$count} tickets") . " confirmed!\n"
            . "{$eventName} · {$eventDate}\n"
            . "View your ticket" . ($count > 1 ? 's' : '') . ": {$confirmationUrl}";

        $formatted = '+' . ltrim($phone, '+');

        $sms->sendSms([$formatted], $message);

        Log::info('SendTicketsSmsJob: complete', ['order' => $order->order_number]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('SendTicketsSmsJob: failed', [
            'order_id' => $this->orderId,
            'error'    => $e->getMessage(),
        ]);
    }
}
