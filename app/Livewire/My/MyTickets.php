<?php

namespace App\Livewire\My;

use App\Enums\PaymentStatus;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app', ['title' => 'My Tickets'])]
class MyTickets extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::with(['event.category', 'tickets.orderItem.ticketType'])
            ->where('user_id', auth()->id())
            ->where('payment_status', PaymentStatus::PAID)
            ->when(
                $this->filter === 'upcoming',
                fn ($q) => $q->whereHas('event', fn ($e) => $e->where('start_at', '>=', now()))
            )
            ->when(
                $this->filter === 'past',
                fn ($q) => $q->whereHas('event', fn ($e) => $e->where('start_at', '<', now()))
            )
            ->latest()
            ->paginate(10);

        return view('livewire.my.my-tickets', compact('orders'));
    }
}
