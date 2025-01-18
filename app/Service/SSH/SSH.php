<?php

namespace App\Service\SSH;

use App\Models\Server;
use Illuminate\Support\Facades\Process;

class SSH
{
    public function __construct(protected Server $server)
    {
        //
    }

    public function ping(): bool
    {
        $command = sprintf('ssh -o BatchMode=yes -o StrictHostKeyChecking=no -o ConnectTimeout=5 -i %s -p %d %s@%s "echo connection_successful"',
            escapeshellarg($this->server->key_file_name),
            $this->server->port,
            escapeshellarg($this->server->username),
            escapeshellarg($this->server->ip)
        );

        $result = Process::run($command);

        return $result->successful() && str_contains($result->output(), 'connection_successful');
    }
}
