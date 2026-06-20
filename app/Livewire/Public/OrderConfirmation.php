<?php

namespace App\Livewire\Public;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Order Confirmed'])]
class OrderConfirmation extends Component
{
    public Order $order;

    public function mount(string $uuid): void
    {
        $query = Order::with(['items.ticketType', 'tickets.orderItem.ticketType', 'event'])
            ->where('uuid', $uuid);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('guest_token', request('token'));
        }

        $this->order = $query->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.order-confirmation');
    }
}
