<?php

namespace App\Livewire\Admin;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class ClearCacheButton extends Component
{
    public bool $clearing = false;

    public function clear(): void
    {
        $this->clearing = true;

        Artisan::call('cache:clear');

        Notification::make()
            ->title('Cache cleared')
            ->success()
            ->send();

        $this->clearing = false;
    }

    public function render()
    {
        return view('livewire.admin.clear-cache-button');
    }
}
