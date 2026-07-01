<?php

namespace App\Filament\Resources\MpesaShortcodes\Pages;

use App\Filament\Resources\MpesaShortcodes\MpesaShortcodeResource;
use Filament\Resources\Pages\EditRecord;

class EditMpesaShortcode extends EditRecord
{
    protected static string $resource = MpesaShortcodeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
