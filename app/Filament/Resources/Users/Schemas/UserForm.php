<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->disabled()
                    ->dehydrated(false)
                    ->hiddenOn('create'),
                TextInput::make('name')
                    ->required(),
                Select::make('role')
                    ->options(['attendee' => 'Attendee', 'organizer' => 'Organizer', 'admin' => 'Admin'])
                    ->default('attendee'),
                TextInput::make('email')
                    ->label('Email address')
                    ->disabled()
                    ->email()
                    ->required(),
                Toggle::make('is_verified')
                    ->label('Email Verified')
                    ->helperText('Toggle to instantly verify or unverify the user.')
                    ->afterStateHydrated(fn ($component, $record) => $component->state($record?->email_verified_at !== null))
                    ->dehydrated(false)
                    ->live()
                    ->afterStateUpdated(function (bool $state, $record) {
                        if ($record) {
                            $record->forceFill(['email_verified_at' => $state ? now() : null])->save();
                        }
                    })
                    ->hiddenOn('create'),

                DateTimePicker::make('email_verified_at')
                    ->nullable()
                    ->helperText('Manually set or clear the verification timestamp.'),
                TextInput::make('password')
                    ->password()
                    ->disabled()
                    ->required(),
            ]);
    }
}
