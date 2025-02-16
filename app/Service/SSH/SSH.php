<?php

namespace App\Service\SSH;

use App\Enums\SSHServerCast;
use App\Models\Server;
use Illuminate\Http\Client\PendingRequest as Client;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SSH
{
    protected Client $client;

    protected Carbon $ttl;

    protected string $endpoint = "";

    protected array $body = [];

    protected string $method = "post";

    public function __construct(
        protected Server $server
    ) {
        $this->ttl = now()->addMinutes(30);

        $this->client = new Client();

        $this->configureClient();
    }

    protected function configureClient(): void
    {
        $sshServerHost = config('services.ssh_server.host');
        $sshServerPort = config('services.ssh_server.port');

        $this->client
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->baseUrl("{$sshServerHost}:{$sshServerPort}");
    }

    protected function logsEnabled(): bool
    {
        return config('services.ssh_server.logs_enabled');
    }

    protected function getLogData(Response $response): array
    {
        return [
            'method'   => $this->method,
            'endpoint' => $this->endpoint,
            'body'     => $this->body,
            'response' => $response->json(),
        ];
    }

    protected function resolveKeyFilePath(): string
    {
        $path     = $this->server->getKeyPath($this->server->key_file_name, $this->server->project->name);
        $basePath = base_path() . '/';

        return str_replace($basePath, '', $path);
    }

    public function connect(): self
    {
        $this->endpoint = '/connect';
        $this->method   = 'post';
        $this->body     = [
            'server_id'        => $this->server->id,
            'host'             => $this->server->ip,
            'username'         => $this->server->username,
            'private_key_path' => $this->resolveKeyFilePath(),
        ];

        return $this;
    }

    public function command(string $command): self
    {
        $this->endpoint = '/command';
        $this->method   = 'post';
        $this->body     = [
            'server_id' => $this->server->id,
            'command'   => $command,
        ];

        return $this;
    }

    public function checkConnection(): self
    {
        $this->endpoint = '/check_connection';
        $this->method   = 'post';
        $this->body     = [
            'server_id' => $this->server->id,
        ];

        return $this;
    }

    public function disconnect(): self
    {
        $this->endpoint = '/disconnect';
        $this->method   = 'post';
        $this->body     = [
            'server_id' => $this->server->id,
        ];

        return $this;
    }

    public function run(SSHServerCast $cast = SSHServerCast::Collection): array|Collection
    {
        $response = $this->client
            ->{$this->method}($this->endpoint, $this->body);

        if ($this->logsEnabled()) {
            logger()
                ->channel('ssh_server')
                ->info('SSH Server', $this->getLogData($response));
        }

        if ($response->collect()->has('error')) {
            logger()
                ->channel('ssh_server')
                ->error('SSH Server', $this->getLogData($response));
        }

        return match ($cast) {
            SSHServerCast::Array      => $response->json(),
            SSHServerCast::Collection => $response->collect(),
            SSHServerCast::Response   => $response,
        };
    }
}
