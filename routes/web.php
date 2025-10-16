<?php

use App\Http\Controllers\ScheduleController;
use App\Livewire\SetNewAccountPassword;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     // phpinfo();
//     $a = 10;
//     $a += 3;
//     $a **= 2;
//     return $a;
// });

Route::get(
    '/newaccount/{user}',
    SetNewAccountPassword::class
)
    ->middleware(['web', 'signed'])
    ->name('newaccount');

// Route::get('/xdebug', function () {
//     xdebug_info();
// });

// Route::get('/ptest', function (): void {
//     $specimen = \App\Models\Specimen::find(640);
//     dd($specimen->project);
// });

Route::middleware('auth')->group(function (): void {
    Route::get('/schedule/{week}', [ScheduleController::class, 'generate']);
});
