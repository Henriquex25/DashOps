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

    public string $command = '';

    #[Locked]
    public string $output     = '';
    public string $erroOutput = '';

    public function mount(Server $server): void
    {
        $this->server = $server;

        $this->checkConnection();
    }

    public function checkConnection(): void
    {
        $response = $this->server
            ->ssh()
            ->connect()
            ->run();

        $this->isConnected = $response->get('status') === SSHServerResponse::Success->value;
    }

    public function runCommand(): void
    {
        $response = $this->server
            ->ssh()
            ->command($this->command)
            ->run();

        if ($response->get('status') === SSHServerResponse::Success->value) {
            $this->output     = $response->get('output');
            $this->erroOutput = $response->get('error');
        }
    }

    public function render(): View
    {
        return view('livewire.server.connected-server');
    }
}
