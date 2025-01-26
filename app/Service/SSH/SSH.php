<?php

namespace App\Service\SSH;

use App\Models\Scopes\OwnerServerScope;
use App\Models\Server;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

class SSH
{
    protected SSH2 $ssh;

    protected Carbon $ttl;

    public function __construct(
        protected Server $server
    ) {
        $this->ttl = now()->addMinutes(30);

        $this->connect();
    }

    protected function connect(): void
    {
        $this->ssh = Cache::remember(
            key     : $this->getCacheKey(),
            ttl     : $this->ttl,
            callback: function () {
                $ssh = new SSH2($this->server->ip, $this->server->port);
                $projectName = $this->server->project?->name ?? Server::withoutGlobalScope(OwnerServerScope::class)
                    ->findOrFail($this->server->project_id);
                $fullKeyPath = $this->server->getKeyPath(projectName: $projectName) . $this->server->key_file_name;
                $privateKey = PublicKeyLoader::load(file_get_contents($fullKeyPath));

                if (!$ssh->login($this->server->username, $privateKey)) {
                    throw new \Exception("SSH authentication failed");
                }

                return $ssh;
            }
        );
    }

    protected function getCacheKey(): string
    {
        ds("ssh_connection_server_" . $this->server->id);
        return "ssh_connection_server_" . $this->server->id;
    }

    public function exec(string $command): string|bool
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        return $this->ssh->exec($command);
    }

    public function isConnected(): bool
    {
        return isset($this->ssh) && $this->ssh->isConnected();
    }

    public function disconnect()
    {
        if (isset($this->ssh)) {
            $this->ssh->disconnect();
        }
    }
}
