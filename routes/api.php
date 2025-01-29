<?php

declare(strict_types = 1);

use App\Http\Controllers\BroadcastController;
use App\Http\Middleware\RestrictToSSHServer;
use Illuminate\Support\Facades\Route;

Route::controller(BroadcastController::class)
    ->middleware([RestrictToSSHServer::class])
    ->prefix('sshserver/')
    ->name('sshserver.')
    ->group(function () {
        Route::post('/broadcast-message', 'broadcastMessage')->name('broadcast-message');
    });
