<?php

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;

use function Pest\Livewire\livewire;

it('can load the page', function () {
    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($users);
});
