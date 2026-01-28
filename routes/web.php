<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\ScheduleController;
use App\Livewire\SetNewAccountPassword;
use Illuminate\Support\Facades\DB;
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

Route::get('/redcap', function () {
    $query = "select app_title, project_id from redcap_projects";
    // $linked_redcap_projects = project::where('redcapProject_id', '<>', 'null')->pluck('redcapProject_id')->toArray();
    // if (count($linked_redcap_projects) > 0) {
    //     $query .= " where project_id not in (" . implode(",", $linked_redcap_projects) . ")";
    // }
    $query .= " order by app_title";
    $redcap_projects = DB::connection('redcap')
        ->select($query);
    dd($redcap_projects);
    // $redcap_projects = collect($redcap_projects)->pluck('app_title', 'project_id')->prepend('', '');
});
