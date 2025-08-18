<?php

use App\Models\User;

it('creates a new user', function () {
    $user = User::factory()->create();
    $this->assertDatabaseHas('users', ['email' => $user->email]);
});
