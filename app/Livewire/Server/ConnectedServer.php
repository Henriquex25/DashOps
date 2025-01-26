<?php

namespace App\Livewire\Server;

use App\Models\Server;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ConnectedServer extends Component
{
    public Server $server;

    public bool $isConnected = false;

    public string $output = '';

    public function mount(Server $server): void
    {
        $this->server = $server;

        $this->output = $this->server->ssh()->exec('pwd');
    }

    public function render(): View
    {
        return view('livewire.server.connected-server');
    }
}
