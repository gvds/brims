<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;

it('returns false for users without the update.event permission', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    expect(auth()->user()?->can('update.event'))->toBeFalse();
});

it('returns true for users with the update.event permission (Gate stubbed)', function (): void {
    $user = User::factory()->create();

    // stub the gate for this test instead of relying on DB-spatie wiring
    Gate::define('update.event', fn ($actor): bool => $actor->id === $user->id);

    $this->actingAs($user);

    expect(auth()->user()?->can('update.event'))->toBeTrue();
});
