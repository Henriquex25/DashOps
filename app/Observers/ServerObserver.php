<?php

namespace App\Observers;

use App\Jobs\DeleteSshKeyJob;
use App\Jobs\GenerateSshKey;
use App\Models\Scopes\OwnerScope;
use App\Models\Server;

class ServerObserver
{
    public function created(Server $server): void
    {
        dispatch(new GenerateSshKey(
            $server->id,
            $server->getResolvedProjectName(),
            $server->key_file_name,
            $server->getKeyPath(),
        ));
    }

    public function forceDeleting(Server $server): void
    {
        $project = $server->project()
            ->withoutGlobalScope(OwnerScope::class)
            ->first();

        dispatch(new DeleteSshKeyJob(
            $server->key_file_name,
            $server->getKeyPath(projectName: $project->name),
        ));
    }
}
