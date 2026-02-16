<?php

use App\Filament\Pages\Login;
use App\Models\User;
use Livewire\Livewire;

it('allows an active user to log in', function (): void {
    $user = User::factory()->create([
        'active' => true,
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->fillForm([
            'username' => $user->username,
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors();

    $this->assertAuthenticatedAs($user);
});

it('prevents an inactive user from logging in', function (): void {
    $user = User::factory()->create([
        'active' => false,
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->fillForm([
            'username' => $user->username,
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['username']);

    $this->assertGuest();
});

it('prevents login with incorrect password for active user', function (): void {
    $user = User::factory()->create([
        'active' => true,
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->fillForm([
            'username' => $user->username,
            'password' => 'wrong-password',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['username']);

    $this->assertGuest();
});
