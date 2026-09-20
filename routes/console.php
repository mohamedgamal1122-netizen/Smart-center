<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('notify:check-overdue')->dailyAt('08:00')->timezone('Africa/Cairo');

Schedule::command('backup:auto --keep=7')->dailyAt('02:00')->timezone('Africa/Cairo');
