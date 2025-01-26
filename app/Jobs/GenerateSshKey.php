<?php

namespace App\Jobs;

use App\Models\Scopes\OwnerServerScope;
use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;

class GenerateSshKey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Server $server;

    public function __construct(
        public int    $serverId,
        public string $projectName,
        public string $keyFileName,
        public string $keyPath,
    ) {
        $this->server = Server::withoutGlobalScope(OwnerServerScope::class)->findOrFail($this->serverId);
    }

    public function handle(): void
    {
        if (!is_dir($this->keyPath)) {
            mkdir($this->keyPath, 0755, true);
        }

        $result = Process::run("ssh-keygen -t rsa -b 4096 -C {$this->projectName} -f {$this->keyPath}{$this->keyFileName}");

        if (!$result->successful()) {
            throw new \Exception($result->output());
        }
    }
}
