<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    Artisan::call('cache:clear');

                    Notification::make()
                        ->title('Cache cleared')
                        ->success()
                        ->send();
                }),
        ];
    }
}
