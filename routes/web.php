<?php

use App\Livewire\Auth\RegisterCompany;
use App\Livewire\Auth\RegisterVendor;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware('guest')->group(function () {
    Route::get('/register-company', RegisterCompany::class)->name('register-company');
});
