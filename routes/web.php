<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StorageAllocationReportController;
use App\Livewire\SetNewAccountPassword;
use Illuminate\Support\Facades\Route;

Route::get(
    '/newaccount/{user}',
    SetNewAccountPassword::class
)
    ->middleware(['web', 'signed'])
    ->name('newaccount');

Route::middleware('auth')->group(function (): void {
    Route::get('/schedule/{week}', ScheduleController::class)->name('schedule');
    Route::get('/labels/print', LabelController::class)->name('labels.print');
    Route::get('/storage-allocations/{storageAllocation}', StorageAllocationReportController::class)->name('storage-allocation-report');
});
