<?php

use App\Http\Controllers\MetaWebhookController;
use App\Livewire\Vendor\Analytics;
use App\Livewire\Vendor\Billing;
use App\Livewire\Vendor\Campaigns;
use App\Livewire\Vendor\CampaignShow;
use App\Livewire\Vendor\CompanySettings;
use App\Livewire\Vendor\Contacts;
use App\Livewire\Vendor\Dashboard;
use App\Livewire\Vendor\GroupContacts;
use App\Livewire\Vendor\Groups;
use App\Livewire\Vendor\ImportContacts;
use App\Livewire\Vendor\Invoices;
use App\Livewire\Vendor\Messages;
use App\Livewire\Vendor\MessageShow;
use App\Livewire\Vendor\MetaSetup;
use App\Livewire\Vendor\Payments;
use App\Livewire\Vendor\SubscriptionHistory;
use App\Livewire\Vendor\Tags;
use App\Livewire\Vendor\TeamMembers;
use App\Livewire\Vendor\Templates;
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
        Route::get('/contacts/import', ImportContacts::class)->name('contacts.import');
        Route::get('/groups', Groups::class)->name('groups');
        Route::get('/groups/{group}', GroupContacts::class)->name('groups.contacts');
        Route::get('/templates', Templates::class)->name('templates');
        Route::get('/campaigns', Campaigns::class)->name('campaigns');
        Route::get('/campaigns/{campaign}', CampaignShow::class )->name('campaigns.show');
        Route::get('/messages', Messages::class)->name('messages');
        Route::get('/analytics', Analytics::class)->name('analytics');
        Route::get('/meta-setup', MetaSetup::class)->name('meta.setup');
        Route::get('/webhooks/meta', [MetaWebhookController::class, 'verify']);
        Route::post('/webhooks/meta', [MetaWebhookController::class, 'webhook']);
        Route::get('/messages/{message}', MessageShow::class)->name('messages.show');
        Route::get('/team-members', TeamMembers::class)->name('team-members')->middleware('role:owner');;
    });

