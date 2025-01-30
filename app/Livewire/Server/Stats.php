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

class Stats extends Component
{
    #[Locked]
    public Server $server;

    #[Locked]
    public array $stats = [
        'cpu'    => '0%',
        'uptime' => '',
        'memory' => [
            'total'       => 0,
            'used'        => 0,
            'description' => '',
            'percentage'  => 0,
        ],
        'storage' => [
            'total'       => 0,
            'used'        => 0,
            'description' => '',
            'percentage'  => 0,
        ],
    ];

    public function mount(): void
    {
        $this->getStats();
    }

    public function getStats(): void
    {
        $this->getMemoryStats();
        $this->getCPUStats();
        $this->getStorageStats();
        $this->getUptime();
    }

    protected function getMemoryStats(): void
    {
        $command  = 'awk \'/MemTotal/ {total=$2} /MemAvailable/ {used=total-$2; printf "%.2f, %.2f", total/1024/1024, used/1024/1024}\' /proc/meminfo';
        $response = $this->runCommand($command);

        if ($response->get('status') === SSHServerResponse::Success->value) {
            $statsData = explode(', ', $response->get('output'));
            $used      = trim($statsData[1]);
            $total     = trim($statsData[0]);
            $stats     = $this->stats;

            $stats['memory']['total']       = $total;
            $stats['memory']['used']        = $used;
            $stats['memory']['description'] = "{$used} GB / {$total} GB";
            $stats['memory']['percentage']  = bcdiv($used * 100, $total, 2);

            $this->stats = $stats;
        }
    }

    protected function getCPUStats(): void
    {
        $command  = 'grep \'cpu \' /proc/stat | awk \'{usage=($2+$4)*100/($2+$4+$5); printf "%.2f%%\n", usage}\'';
        $response = $this->runCommand($command);

        if ($response->get('status') === SSHServerResponse::Success->value) {
            $this->stats['cpu'] = $response->get('output');
        }
    }

    protected function getStorageStats(): void
    {
        $command  = 'df -h / | awk \'NR==2 {print $2", "$3}\'';
        $response = $this->runCommand($command);

        if ($response->get('status') === SSHServerResponse::Success->value) {
            $stats     = $this->stats;
            $statsData = explode(', ', $response->get('output'));
            $total     = preg_replace('/[^0-9.]/', '', $statsData[0]);
            $used      = preg_replace('/[^0-9.]/', '', $statsData[1]);

            $stats['storage']['total']       = $total;
            $stats['storage']['used']        = $used;
            $stats['storage']['description'] = "{$used} GB / {$total} GB";
            $stats['storage']['percentage']  = bcdiv($used * 100, $total, 2);

            $this->stats = $stats;
        }
    }

    protected function getUptime(): void
    {
        $command  = "uptime -p | sed 's/up/Uptime:/'";
        $response = $this->runCommand($command);

        if ($response->get('status') === SSHServerResponse::Success->value) {
            $this->stats['uptime'] = mb_substr(trim($response->get('output')), 8);
        }
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
        return view('livewire.server.stats');
    }
}
