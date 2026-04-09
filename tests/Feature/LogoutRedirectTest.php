<?php

use App\Enums\SystemRoles;
use App\Models\User;

it('redirects to the app panel login page when logging out from the admin panel', function () {
    $user = User::factory()->create(['system_role' => SystemRoles::SuperAdmin]);

    $response = $this->actingAs($user)
        ->post(route('filament.admin.auth.logout'));

    $response->assertRedirect(route('filament.app.auth.login'));
});

it('redirects to the app panel login page when logging out from the project panel', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withSession(['currentProject' => 1])
        ->post(route('filament.project.auth.logout'));

    $response->assertRedirect(route('filament.app.auth.login'));
});

it('redirects to the app panel login page when logging out from the app panel', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('filament.app.auth.logout'));

    $response->assertRedirect(route('filament.app.auth.login'));
});
