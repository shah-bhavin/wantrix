<?php

use App\Console\Commands\ExpireSubscriptionsCommand;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(
    ExpireSubscriptionsCommand::class
)->daily();
// Process scheduled campaigns every minute
Schedule::command(
    'app:process-scheduled-campaigns'
)->everyMinute();