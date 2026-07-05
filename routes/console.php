<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('crm:scan-reminders')
    ->dailyAt('00:00')
    ->withoutOverlapping(10);

Schedule::command('crm:send-reminders')
    ->everyFiveMinutes()
    ->withoutOverlapping(10);
