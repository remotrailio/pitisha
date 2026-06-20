<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DownloadTicketsPdfController extends Controller
{
    public function __invoke(Request $request, string $uuid): Response
    {
        $query = Order::with([
            'tickets.orderItem.ticketType',
            'event',
            'items.ticketType',
            'user',
        ])->where('uuid', $uuid);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('guest_token', $request->query('token'));
        }

        $order = $query->firstOrFail();

        $pdf = app('dompdf.wrapper')
            ->loadView('pdf.tickets', ['order' => $order])
            ->setPaper('a4', 'portrait');

        return $pdf->download('tickets-' . $order->order_number . '.pdf');
    }
}
