<?php

use App\Models\User;

it('creates a new user', function (): void {
    $user = User::factory()->create();
    $this->assertDatabaseHas('users', ['email' => $user->email]);
});
