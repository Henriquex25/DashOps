<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteSshKeyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $keyFileName,
        public string $keyPath
    ) {
    }

    public function handle(): void
    {
        if (file_exists($this->keyPath)) {
            unlink($this->keyPath . $this->keyFileName);
            unlink($this->keyPath . $this->keyFileName . '.pub');
        }
    }
}
