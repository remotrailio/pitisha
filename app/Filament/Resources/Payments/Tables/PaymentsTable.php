<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('status')
                    ->icon(fn (PaymentStatus $state): string => match ($state) {
                        PaymentStatus::PAID       => 'heroicon-o-check-circle',
                        PaymentStatus::PROCESSING => 'heroicon-o-clock',
                        PaymentStatus::FAILED     => 'heroicon-o-x-circle',
                        PaymentStatus::UNKNOWN    => 'heroicon-o-question-mark-circle',
                        PaymentStatus::REFUNDED   => 'heroicon-o-arrow-uturn-left',
                        default                   => 'heroicon-o-ellipsis-horizontal-circle',
                    })
                    ->color(fn (PaymentStatus $state): string => match ($state) {
                        PaymentStatus::PAID       => 'success',
                        PaymentStatus::PROCESSING => 'warning',
                        PaymentStatus::FAILED     => 'danger',
                        PaymentStatus::UNKNOWN    => 'gray',
                        PaymentStatus::REFUNDED   => 'info',
                        default                   => 'gray',
                    })
                    ->tooltip(fn (PaymentStatus $state): string => $state->value),

                TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('order.user.name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('provider')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('method')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('amount')
                    ->money(fn (Payment $record): string => $record->currency)
                    ->sortable(),

                TextColumn::make('phone')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('receipt_number')
                    ->label('Receipt #')
                    ->placeholder('—')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('checkout_request_id')
                    ->label('Checkout ID')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Initiated')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        PaymentStatus::PROCESSING->value => 'Processing',
                        PaymentStatus::PAID->value       => 'Paid',
                        PaymentStatus::FAILED->value     => 'Failed',
                        PaymentStatus::UNKNOWN->value    => 'Unknown',
                        PaymentStatus::REFUNDED->value   => 'Refunded',
                    ]),

                SelectFilter::make('provider')
                    ->options(fn () => \App\Models\Payment::query()
                        ->whereNotNull('provider')
                        ->distinct()
                        ->pluck('provider', 'provider')
                        ->all()),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
