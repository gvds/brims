<?php

use App\Livewire\SetNewAccountPassword;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(SetNewAccountPassword::class)
        ->assertStatus(200);
});
