<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:delete-old-exports')
    ->hourly();

Schedule::command('app:deactivate-inactive-users')
    ->daily();
