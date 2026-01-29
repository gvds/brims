<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\ScheduleController;
use App\Livewire\SetNewAccountPassword;
use Illuminate\Support\Facades\Route;

Route::get(
    '/newaccount/{user}',
    SetNewAccountPassword::class
)
    ->middleware(['web', 'signed'])
    ->name('newaccount');

// Route::get('/xdebug', function () {
//     xdebug_info();
// });

Route::middleware('auth')->group(function (): void {
    Route::get('/schedule/{week}', [ScheduleController::class, 'generate']);
    Route::get('/labels/print', LabelController::class)->name('labels.print');
});
