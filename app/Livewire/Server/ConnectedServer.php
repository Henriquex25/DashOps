<?php

namespace App\Livewire\Server;

use App\Enums\SSHServerCast;
use App\Enums\SSHServerResponse;
use App\Models\Server;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ConnectedServer extends Component
{
    #[Locked]
    public Server $server;

    public bool $isConnected = false;

    public string $command = '';

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

    protected function runCommand(string $command, SSHServerCast $cast = SSHServerCast::Collection): Collection | array | Response
    {
        return $this->server
            ->ssh()
            ->command($command)
            ->run($cast);
    }

    public function render(): View
    {
        return view('livewire.server.connected-server');
    }
}
