<?php

namespace Tests;

use App\Models\Team;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

// beforeEach(function () {
//     /** @var \App\Models\User $user */
//     $user = User::factory()->create();

//     /** @var \App\Models\Team $team */
//     $team = Team::factory()->create([
//         'leader_id' => $user->id,
//     ]);

//     $user->update([
//         'team_id' => $team->id,
//         'team_role' => 'Admin',
//     ]);

//     actingAs($user);
// });

abstract class TestCase extends BaseTestCase
{
    protected Team $team;
    protected User $adminuser;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $user->assignRole('super_admin');

        /** @var \App\Models\Team $team */
        $team = Team::factory()->create([
            'leader_id' => $user->id,
        ]);
        $this->team = $team;

        $user->update([
            'team_id' => $team->id,
            'team_role' => 'Admin',
        ]);
        $this->adminuser = $user;
        // actingAs($this->user);
    }
}
