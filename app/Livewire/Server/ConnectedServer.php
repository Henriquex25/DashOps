<?php

namespace App\Livewire\Server;

use App\Enums\SSHServerResponse;
use App\Models\Server;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ConnectedServer extends Component
{
    #[Locked]
    public Server $server;

    public bool $isConnected = false;

    public function mount(Server $server): void
    {
        $this->server = $server;
    }

    public function checkConnection(): void
    {
        $response = $this->server
            ->ssh()
            ->connect()
            ->run();

        $this->isConnected = $response->get('status') === SSHServerResponse::Success->value;
    }

    public function render(): View
    {
        return view('livewire.server.connected-server');
    }
}
