<?php

use App\Livewire\Dashboard;
use App\Livewire\Project\IndexProject;
use App\Livewire\Server\ConnectedServer;
use App\Livewire\Server\IndexServer;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index');
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)
        ->name('dashboard.index');

    Route::get('/projects', IndexProject::class)
        ->name('projects.index');

    Route::prefix('/servers')->group(function () {
        Route::get('/', IndexServer::class)
            ->name('servers.index');

        Route::get('/connected/{server}', ConnectedServer::class)
            ->name('servers.connected');
    });
});

require __DIR__ . '/auth.php';
