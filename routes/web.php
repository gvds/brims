<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StorageAllocationReportController;
use App\Http\Middleware\SetUserTeam;
use App\Livewire\SetNewAccountPassword;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

Route::get(
    '/newaccount/{user}',
    SetNewAccountPassword::class
)
    ->middleware(['web', 'signed'])
    ->name('newaccount');

Route::middleware('auth')->group(function (): void {

    Route::middleware([SetUserTeam::class, 'can:Manage:Subject'])->group(function (): void {
        Route::get('/schedule/{week}', ScheduleController::class)->name('schedule');
        Route::get('/labels/print', LabelController::class)->name('labels.print');
    });

    Route::get('/documentation', fn(): BinaryFileResponse => response()
        ->file(base_path('docs/documentation.html')))
        ->name('documentation');

    Route::get('/storage-allocations/{storageAllocation}', StorageAllocationReportController::class)
        ->name('storage-allocation-report');
});
