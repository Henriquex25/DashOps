<?php

use App\Models\Server;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('server.{serverID}', function ($user, $serverId) {
    return Server::where('id', $serverId)->exists();
});
