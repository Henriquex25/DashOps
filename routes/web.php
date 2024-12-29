<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Dashboard;
use App\Livewire\Projects;
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

    Route::get('/projects', Projects::class)
        ->name('projects');
});

require __DIR__ . '/auth.php';
