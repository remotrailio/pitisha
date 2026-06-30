<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:expire-orders')->everyTenMinutes();

Schedule::job(new \App\Jobs\MarkEventsAsLiveJob)->hourly()->onOneServer();
Schedule::job(new \App\Jobs\MarkEventsAsEndedJob)->hourly()->onOneServer();

// Catch any payments whose STK push callback was never received
Schedule::job(new \App\Jobs\ReconcilePendingPaymentsJob)->everyThirtyMinutes()->onOneServer();