<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Solutions extends Component
{
    public function render()
    {
        return view('livewire.public.solutions')
            ->layout('layouts.app', ['title' => 'Solutions']);
    }
}
