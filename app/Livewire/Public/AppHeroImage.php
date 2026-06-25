<?php

namespace App\Livewire\Public;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.blank', ['title' => 'Hero Image'])]
class AppHeroImage extends Component
{
    public function render()
    {
        return view('livewire.public.app-hero-image');
    }
}
