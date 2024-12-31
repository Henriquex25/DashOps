<?php

use App\Livewire\Dashboard;
use App\Livewire\Project\IndexProject;
use App\Livewire\Project\Project;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)
        ->name('dashboard');

    Route::get('/projects', IndexProject::class)
        ->name('projects');
});

require __DIR__ . '/auth.php';
