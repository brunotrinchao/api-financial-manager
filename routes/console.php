<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::command('updateTransactionsStatus:cron')->dailyAt('9:49');
Schedule::command('updateTransactionsOverdue:cron')->dailyAt('3:50');
