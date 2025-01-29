<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateTokenForSSHServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssh-server:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command generates a token so that the ssh server can communicate with this application.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path        = base_path('ssh_server/.env');
        $token       = config('services.ssh_server.token');
        $hashedToken = Hash::make($token);
        $key         = "API_TOKEN=";
        $data        = "{$key}'{$hashedToken}'\n";

        if (!file_exists($path)) {
            file_put_contents($path, $data);

            return;
        }

        $env        = file_get_contents($path);
        $tokenAdded = false;

        foreach (explode("\n", $env) as $line) {
            if (str_contains($line, $key)) {
                $env        = str_replace($line, $data, $env);
                $tokenAdded = true;

                break;
            }
        }

        if (!$tokenAdded) {
            $env .= $data;
        }

        file_put_contents($path, $env);

        $this->info('Token generated successfully.');
    }
}
