<?php

use App\Livewire\Vendor\Billing;
use App\Livewire\Vendor\CompanySettings;
use App\Livewire\Vendor\Contacts;
use App\Livewire\Vendor\Dashboard;
use App\Livewire\Vendor\Invoices;
use App\Livewire\Vendor\Payments;
use App\Livewire\Vendor\SubscriptionHistory;
use App\Livewire\Vendor\Tags;
use App\Livewire\Vendor\UpgradePlan;
use App\Livewire\Vendor\WhatsappAccounts;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('app')
    ->name('vendor.')
    ->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/billing', Billing::class)->name('billing');
        Route::get('/billing/upgrade', UpgradePlan::class)->name('billing.upgrade');
        Route::get('/billing/invoices', Invoices::class)->name('billing.invoices');
        Route::get('/billing/payments', Payments::class)->name('billing.payments');
        Route::get('/billing/history', SubscriptionHistory::class)->name('billing.history');
        Route::get('/settings/company', CompanySettings::class)->name('settings.company');
        Route::get('/whatsapp-accounts', WhatsappAccounts::class)->name('whatsapp.accounts');
        Route::get('/contacts', Contacts::class)->name('contacts');
        Route::get('/tags', Tags::class)->name('tags');
    });

