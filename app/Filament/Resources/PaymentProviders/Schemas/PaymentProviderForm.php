<?php

namespace App\Filament\Resources\PaymentProviders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PaymentProviderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->placeholder('Safaricom M-Pesa'),

            TextInput::make('slug')
                ->required()
                ->maxLength(50)
                ->placeholder('mpesa')
                ->helperText('Lowercase, no spaces. Used internally to identify the provider.')
                ->unique(ignoreRecord: true),

            TextInput::make('description')
                ->nullable()
                ->maxLength(255)
                ->placeholder('Pay via M-Pesa STK push'),

            FileUpload::make('logo_path')
                ->label('Logo')
                ->image()
                ->disk('r2')
                ->directory(app()->isLocal() ? 'local/payment-providers' : 'payment-providers')
                ->visibility('public')
                ->nullable(),

            TextInput::make('sort_order')
                ->label('Display order')
                ->numeric()
                ->default(0)
                ->helperText('Lower numbers appear first on the checkout page.'),

            Toggle::make('is_active')
                ->label('Active')
                ->helperText('Only active providers are shown at checkout.'),
        ]);
    }
}
