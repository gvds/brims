<?php

use App\Enums\TeamRoles;
use App\Filament\Resources\Teams\RelationManagers\MembersRelationManager;
use App\Models\Team;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the members relation manager', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ])->assertSuccessful();
});

it('displays existing team members', function () {
    $team = Team::factory()->create();
    $teamAdminUser = User::factory()->create([
        'team_id' => $team->id,
        'team_role' => TeamRoles::Admin,
        'active' => true,
    ]);
    $memberUser = User::factory()->create([
        'team_id' => $team->id,
        'team_role' => TeamRoles::Member,
        'active' => true,
    ]);

    $this->actingAs($teamAdminUser);

    Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ])
        ->assertCanSeeTableRecords([$teamAdminUser, $memberUser]);
});

it('shows user details in the table', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create([
        'team_id' => $team->id,
        'team_role' => TeamRoles::Admin,
        'active' => true,
        'firstname' => 'John',
        'lastname' => 'Doe',
        'email' => 'john.doe@example.com',
        'username' => 'johndoe',
        'telephone' => '555-0123',
    ]);

    $this->actingAs($user);

    Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ])
        ->assertSee('John')
        ->assertSee('Doe')
        ->assertSee('john.doe@example.com')
        ->assertSee('johndoe')
        ->assertSee('555-0123');
});

it('can filter by active status', function () {
    $team = Team::factory()->create();
    $activeUser = User::factory()->create([
        'team_id' => $team->id,
        'active' => true,
        'username' => 'activeuser',
    ]);
    $inactiveUser = User::factory()->create([
        'team_id' => $team->id,
        'active' => false,
        'username' => 'inactiveuser',
    ]);

    $this->actingAs($activeUser);

    $component = Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ]);

    // Initially both users should be visible
    $component->assertCanSeeTableRecords([$activeUser, $inactiveUser]);

    // Test filtering by active (toggle filter shows only active when turned on)
    $component->filterTable('active')
        ->assertCanSeeTableRecords([$activeUser])
        ->assertCanNotSeeTableRecords([$inactiveUser]);
});

it('can filter by homesite', function () {
    $team = Team::factory()->create();
    $user1 = User::factory()->create([
        'team_id' => $team->id,
        'homesite' => 'Main Office',
    ]);
    $user2 = User::factory()->create([
        'team_id' => $team->id,
        'homesite' => 'Branch Office',
    ]);

    $this->actingAs($user1);

    $component = Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ]);

    $component->filterTable('homesite', 'Main Office')
        ->assertCanSeeTableRecords([$user1])
        ->assertCanNotSeeTableRecords([$user2]);
});

it('can edit existing team members', function () {
    $team = Team::factory()->create();
    $member = User::factory()->create([
        'team_id' => $team->id,
        'username' => 'oldusername',
        'firstname' => 'Old',
        'lastname' => 'Name',
        'team_role' => TeamRoles::Member,
    ]);
    $user = User::factory()->create();

    $this->actingAs($this->adminuser);

    Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ])
        ->assertActionExists(TestAction::make('edit')->table($member))
        ->callAction(TestAction::make('edit')->table($member), data: [
            'username' => 'newusername',
            'firstname' => 'New',
            'lastname' => 'Name',
            'team_role' => TeamRoles::Admin,
        ])
        ->assertNotified();

    $member->refresh();
    expect($member->username)->toBe('newusername');
    expect($member->firstname)->toBe('New');
    expect($member->team_role)->toBe(TeamRoles::Admin->value);
});

it('can delete team members', function () {
    $team = Team::factory()->create();
    $member = User::factory()->create([
        'team_id' => $team->id,
    ]);
    $user = User::factory()->create();

    $this->actingAs($this->adminuser);

    Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ])
        ->assertActionExists(TestAction::make('delete')->table($member))
        ->callAction(TestAction::make('delete')->table($member))
        ->assertNotified();

    $this->assertModelMissing($member);
});

it('only shows members for the specific team', function () {
    $team1 = Team::factory()->create();
    $team2 = Team::factory()->create();

    $team1Member = User::factory()->create([
        'team_id' => $team1->id,
        'username' => 'team1user',
    ]);
    $team2Member = User::factory()->create([
        'team_id' => $team2->id,
        'username' => 'team2user',
    ]);
    $noTeamMember = User::factory()->create([
        'team_id' => null,
        'username' => 'noteamuser',
    ]);

    $this->actingAs($team1Member);

    Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team1,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ])
        ->assertCanSeeTableRecords([$team1Member])
        ->assertCanNotSeeTableRecords([$team2Member, $noTeamMember]);
});

it('displays team role correctly', function () {
    $team = Team::factory()->create();
    $adminUser = User::factory()->create([
        'team_id' => $team->id,
        'team_role' => TeamRoles::Admin,
    ]);
    $memberUser = User::factory()->create([
        'team_id' => $team->id,
        'team_role' => TeamRoles::Member,
    ]);

    $this->actingAs($adminUser);

    Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ])
        ->assertSee('Admin')
        ->assertSee('Member');
});

it('shows active status correctly', function () {
    $team = Team::factory()->create();
    $activeUser = User::factory()->create([
        'team_id' => $team->id,
        'active' => true,
    ]);
    $inactiveUser = User::factory()->create([
        'team_id' => $team->id,
        'active' => false,
    ]);

    $this->actingAs($activeUser);

    $component = Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ]);

    // Check that both users are visible in the table (icons show boolean status)
    $component->assertCanSeeTableRecords([$activeUser, $inactiveUser]);
});

it('can search members by name', function () {
    $team = Team::factory()->create();
    $user1 = User::factory()->create([
        'team_id' => $team->id,
        'firstname' => 'John',
        'lastname' => 'Doe',
    ]);
    $user2 = User::factory()->create([
        'team_id' => $team->id,
        'firstname' => 'Jane',
        'lastname' => 'Smith',
    ]);

    $this->actingAs($user1);

    Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ])
        ->searchTable('John')
        ->assertCanSeeTableRecords([$user1])
        ->assertCanNotSeeTableRecords([$user2])
        ->searchTable('Jane')
        ->assertCanSeeTableRecords([$user2])
        ->assertCanNotSeeTableRecords([$user1]);
});

it('can search members by username', function () {
    $team = Team::factory()->create();
    $user1 = User::factory()->create([
        'team_id' => $team->id,
        'username' => 'johndoe',
    ]);
    $user2 = User::factory()->create([
        'team_id' => $team->id,
        'username' => 'janesmith',
    ]);

    $this->actingAs($user1);

    Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ])
        ->searchTable('johndoe')
        ->assertCanSeeTableRecords([$user1])
        ->assertCanNotSeeTableRecords([$user2]);
});

it('can search members by email', function () {
    $team = Team::factory()->create();
    $user1 = User::factory()->create([
        'team_id' => $team->id,
        'email' => 'john@example.com',
    ]);
    $user2 = User::factory()->create([
        'team_id' => $team->id,
        'email' => 'jane@example.com',
    ]);

    $this->actingAs($user1);

    Livewire::test(MembersRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => \App\Filament\Resources\Teams\Pages\EditTeam::class,
    ])
        ->searchTable('john@example.com')
        ->assertCanSeeTableRecords([$user1])
        ->assertCanNotSeeTableRecords([$user2]);
});
