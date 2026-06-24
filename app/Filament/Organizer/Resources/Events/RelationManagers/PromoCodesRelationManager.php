<?php

namespace App\Filament\Organizer\Resources\Events\RelationManagers;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PromoCodesRelationManager extends RelationManager
{
    protected static string $relationship = 'promoCodes';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(100),

            TextInput::make('code')
                ->required()
                ->maxLength(50)
                ->formatStateUsing(fn ($state) => strtoupper($state ?? ''))
                ->dehydrateStateUsing(fn ($state) => strtoupper($state ?? '')),

            Select::make('type')
                ->options(['percentage' => 'Percentage', 'fixed' => 'Fixed Amount'])
                ->required(),

            TextInput::make('value')
                ->numeric()
                ->required()
                ->minValue(0),

            TextInput::make('max_uses')
                ->numeric()
                ->required()
                ->minValue(1)
                ->label('Max Uses'),

            TextInput::make('minimum_order_amount')
                ->numeric()
                ->label('Minimum Order Amount'),

            DateTimePicker::make('starts_at'),

            DateTimePicker::make('expires_at'),

            Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('type')->badge()
                    ->color(fn ($state) => $state === 'percentage' ? 'info' : 'warning'),
                TextColumn::make('value')
                    ->formatStateUsing(fn ($state, $record) => $record->type === 'percentage'
                        ? "{$state}%" : "KES {$state}"),
                TextColumn::make('used_count')->label('Used'),
                TextColumn::make('max_uses')->label('Max'),
                TextColumn::make('expires_at')->dateTime()->placeholder('No expiry'),
                IconColumn::make('is_active')->boolean()->label('Active'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
