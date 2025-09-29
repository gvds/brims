<?php

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

Route::get('/ptest', function () {
    $specimen = \App\Models\Specimen::find(640);
    dd($specimen->project);
});
