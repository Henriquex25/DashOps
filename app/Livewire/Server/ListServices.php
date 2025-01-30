<?php

namespace App\Livewire\Server;

use App\Models\Server;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ListServices extends Component
{
    #[Locked]
    public Server $server;

    public function mount(): void
    {
        //
    }

    public function render(): View
    {
        return view('livewire.server.list-services');
    }
}
