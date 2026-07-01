<?php

namespace App\Filament\Resources\MpesaShortcodes\Tables;

use App\Models\MpesaShortcode;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class MpesaShortcodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(fn ($record) => $record->is_active ? 'bold' : 'normal'),

                TextColumn::make('shortcode')
                    ->searchable(),

                TextColumn::make('payments_count')
                    ->label('Payments')
                    ->counts('payments')
                    ->sortable(),

                TextColumn::make('activated_at')
                    ->label('Last activated')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Never'),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('is_active', 'desc')
            ->recordActions([
                Action::make('activate')
                    ->label('Set as active')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn (MpesaShortcode $record) => $record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Activate this shortcode?')
                    ->modalDescription(fn (MpesaShortcode $record) => new HtmlString(
                        "All new payments will use shortcode <strong>{$record->shortcode}</strong> ({$record->name}). Existing orders are not affected."
                    ))
                    ->action(fn (MpesaShortcode $record) => $record->activate()),

                EditAction::make(),
            ]);
    }
}
