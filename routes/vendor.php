<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Vendor\Dashboard;

Route::middleware(['auth'])
    ->prefix('app')
    ->name('vendor.')
    ->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
    });
