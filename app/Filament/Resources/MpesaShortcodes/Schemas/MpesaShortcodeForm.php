<?php

namespace App\Filament\Resources\MpesaShortcodes\Schemas;

use App\Models\PaymentProvider;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MpesaShortcodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('payment_provider_id')
                ->label('Payment Provider')
                ->options(fn () => PaymentProvider::orderBy('name')->pluck('name', 'id'))
                ->required()
                ->searchable(),

            TextInput::make('name')
                ->label('Label')
                ->placeholder('e.g. Primary, Backup 2025')
                ->required()
                ->maxLength(255),

            TextInput::make('shortcode')
                ->label('Shortcode')
                ->placeholder('174379')
                ->required()
                ->maxLength(20),

            TextInput::make('passkey')
                ->label('Passkey')
                ->password()
                ->revealable()
                ->required()
                ->maxLength(500)
                ->helperText('Encrypted at rest. Leave blank to keep existing value when editing.')
                ->dehydrateStateUsing(fn ($state) => filled($state) ? $state : null)
                ->dehydrated(fn ($state) => filled($state)),

            Textarea::make('notes')
                ->label('Notes')
                ->rows(3)
                ->nullable(),

            Placeholder::make('is_active')
                ->label('Status')
                ->content(fn ($record) => $record?->is_active ? 'Active' : 'Inactive')
                ->visibleOn('edit'),
        ]);
    }
}
