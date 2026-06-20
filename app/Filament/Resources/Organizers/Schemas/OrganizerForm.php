<?php

namespace App\Filament\Resources\Organizers\Schemas;

use App\Enums\OrganizerStatus;
use App\Enums\UserRole;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OrganizerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label('User')
                ->options(
                    User::whereDoesntHave('organizer')
                        ->where('role', '!=', UserRole::ADMIN)
                        ->orderBy('name')
                        ->get()
                        ->mapWithKeys(fn (User $u) => [$u->id => "{$u->name} ({$u->email})"])
                )
                ->searchable()
                ->required()
                ->hiddenOn('edit')
                ->helperText('Only users without an existing organizer account are listed.'),

            TextInput::make('display_name')
                ->required(),

            TextInput::make('slug')
                ->unique(table: 'organizers', column: 'slug', ignoreRecord: true)
                ->helperText('Leave blank to auto-generate from display name.')
                ->nullable(),

            Textarea::make('bio')
                ->nullable(),

            FileUpload::make('logo')
                ->image()
                ->disk('r2')
                ->directory(app()->isLocal() ? 'local/organizers/logo' : 'organizers/logo')
                ->visibility('public')
                ->nullable(),

            FileUpload::make('banner')
                ->label('Banner Image')
                ->image()
                ->disk('r2')
                ->directory(app()->isLocal() ? 'local/organizers/banner' : 'organizers/banner')
                ->visibility('public')
                ->nullable(),

            Select::make('email')
                ->nullable()
                ->options(User::orderBy('email')->pluck('email', 'email'))
                ->searchable(),

            TextInput::make('phone')
                ->nullable(),

            TextInput::make('platform_fee_percentage')
                ->label('Platform Fee (%)')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->step(0.01)
                ->suffix('%')
                ->default(10)
                ->required(),

            Toggle::make('verified')
                ->default(false),

            Select::make('status')
                ->options([
                    OrganizerStatus::ACTIVE->value    => 'Active',
                    OrganizerStatus::SUSPENDED->value => 'Suspended',
                ])
                ->default(OrganizerStatus::ACTIVE->value)
                ->required(),
        ]);
    }
}
