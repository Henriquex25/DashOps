<?php

namespace App\Livewire;

use Livewire\Component;

class AppLayout extends Component
{
    public function render()
    {
        return view('components.layouts.app')
            ->layout('layouts.base');
    }
}
