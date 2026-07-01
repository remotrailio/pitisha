<?php

namespace App\Filament\Resources\Payments\Infolists;

use App\Models\Payment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ── Status & amounts ───────────────────────────────────────────
            TextEntry::make('status')
                ->badge(),

            TextEntry::make('amount')
                ->money(fn (Payment $record): string => $record->currency),

            TextEntry::make('provider')
                ->badge()
                ->placeholder('—'),

            TextEntry::make('method')
                ->placeholder('—'),

            // ── M-Pesa identifiers ─────────────────────────────────────────
            TextEntry::make('phone')
                ->label('Phone')
                ->placeholder('—'),

            TextEntry::make('checkout_request_id')
                ->label('Checkout Request ID')
                ->placeholder('—')
                ->copyable(),

            TextEntry::make('merchant_request_id')
                ->label('Merchant Request ID')
                ->placeholder('—')
                ->copyable(),

            TextEntry::make('receipt_number')
                ->label('M-Pesa Receipt #')
                ->placeholder('—')
                ->copyable(),

            TextEntry::make('reference')
                ->label('Payment Reference')
                ->placeholder('—')
                ->copyable(),

            // ── Reconciliation ─────────────────────────────────────────────
            TextEntry::make('status_query_attempts')
                ->label('Query Attempts'),

            TextEntry::make('last_status_query_at')
                ->label('Last Queried')
                ->dateTime()
                ->placeholder('—'),

            TextEntry::make('callback_received_at')
                ->label('Callback Received')
                ->dateTime()
                ->placeholder('—'),

            TextEntry::make('failure_reason')
                ->label('Failure Reason')
                ->placeholder('—')
                ->columnSpanFull(),

            // ── Timing ─────────────────────────────────────────────────────
            TextEntry::make('initiated_at')
                ->dateTime()
                ->placeholder('—'),

            TextEntry::make('completed_at')
                ->dateTime()
                ->placeholder('—'),

            TextEntry::make('created_at')
                ->label('Created')
                ->dateTime(),

            // ── Linked records ─────────────────────────────────────────────
            TextEntry::make('order.order_number')
                ->label('Order #')
                ->copyable(),

            TextEntry::make('order.user.name')
                ->label('Customer'),

            TextEntry::make('order.event.title')
                ->label('Event'),

            TextEntry::make('shortcode')
                ->label('Shortcode')
                ->placeholder('—'),
        ]);
    }
}
