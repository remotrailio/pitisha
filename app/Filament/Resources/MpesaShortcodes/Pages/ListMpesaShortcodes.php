<?php

namespace App\Filament\Resources\MpesaShortcodes\Pages;

use App\Filament\Resources\MpesaShortcodes\MpesaShortcodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMpesaShortcodes extends ListRecords
{
    protected static string $resource = MpesaShortcodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
