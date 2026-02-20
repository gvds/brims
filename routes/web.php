<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\ScheduleController;
use App\Livewire\SetNewAccountPassword;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get(
    '/newaccount/{user}',
    SetNewAccountPassword::class
)
    ->middleware(['web', 'signed'])
    ->name('newaccount');

Route::middleware('auth')->group(function (): void {
    Route::get('/schedule/{week}', [ScheduleController::class, 'generate']);
    Route::get('/labels/print', LabelController::class)->name('labels.print');
});
