<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Daily payment reminders at 08:00 local time
Schedule::command('notifications:payment-reminders')
    ->dailyAt('08:00')
    ->onOneServer()
    ->withoutOverlapping();

// Lesson reminders every 5 minutes during school hours
Schedule::command('lessons:dispatch-reminders')
    ->everyFiveMinutes()
    ->onOneServer()
    ->withoutOverlapping();
