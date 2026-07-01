<?php

namespace App\Filament\Resources\MpesaShortcodes;

use App\Filament\Resources\MpesaShortcodes\Pages\CreateMpesaShortcode;
use App\Filament\Resources\MpesaShortcodes\Pages\EditMpesaShortcode;
use App\Filament\Resources\MpesaShortcodes\Pages\ListMpesaShortcodes;
use App\Filament\Resources\MpesaShortcodes\Schemas\MpesaShortcodeForm;
use App\Filament\Resources\MpesaShortcodes\Tables\MpesaShortcodesTable;
use App\Models\MpesaShortcode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MpesaShortcodeResource extends Resource
{
    protected static ?string $model = MpesaShortcode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $navigationLabel = 'M-Pesa Shortcodes';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): string
    {
        return 'Payments';
    }

    protected static ?string $modelLabel = 'Shortcode';

    public static function form(Schema $schema): Schema
    {
        return MpesaShortcodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MpesaShortcodesTable::configure($table);
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListMpesaShortcodes::route('/'),
            'create' => CreateMpesaShortcode::route('/create'),
            'edit'   => EditMpesaShortcode::route('/{record}/edit'),
        ];
    }
}
