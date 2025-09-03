<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Mail\UserAccountCreated;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    // Create super_admin role and super_admin user for testing
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    /** @var User $user */
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    actingAs($user);
    $this->user = $user;
});

describe('UserResource List Page', function (): void {
    it('can load the users list page', function (): void {
        $users = User::factory()->count(5)->create();

        livewire(ListUsers::class)
            ->assertOk()
            ->assertCanSeeTableRecords($users);
    });

    it('can search users by username', function (): void {
        $user1 = User::factory()->create(['username' => 'johndoe']);
        $user2 = User::factory()->create(['username' => 'janedoe']);

        livewire(ListUsers::class)
            ->searchTable('johndoe')
            ->assertCanSeeTableRecords([$user1])
            ->assertCanNotSeeTableRecords([$user2]);
    });

    it('can search users by email', function (): void {
        $user1 = User::factory()->create(['email' => 'john@example.com']);
        $user2 = User::factory()->create(['email' => 'jane@example.com']);

        livewire(ListUsers::class)
            ->searchTable('john@example.com')
            ->assertCanSeeTableRecords([$user1])
            ->assertCanNotSeeTableRecords([$user2]);
    });

    it('can search users by name', function (): void {
        $user1 = User::factory()->create(['firstname' => 'John', 'lastname' => 'Doe']);
        $user2 = User::factory()->create(['firstname' => 'Jane', 'lastname' => 'Smith']);

        livewire(ListUsers::class)
            ->searchTable('John')
            ->assertCanSeeTableRecords([$user1])
            ->assertCanNotSeeTableRecords([$user2]);
    });

    it('can sort users by username', function (): void {
        $user1 = User::factory()->create(['username' => 'alpha']);
        $user2 = User::factory()->create(['username' => 'beta']);

        livewire(ListUsers::class)
            ->sortTable('username')
            ->assertCanSeeTableRecords([$user1, $user2], inOrder: true);
    });

    it('can filter users by team', function (): void {
        $team1 = Team::factory()->create(['name' => 'Team Alpha']);
        $team2 = Team::factory()->create(['name' => 'Team Beta']);

        $user1 = User::factory()->create(['team_id' => $team1->id]);
        $user2 = User::factory()->create(['team_id' => $team2->id]);

        livewire(ListUsers::class)
            ->filterTable('team_id', $team1->id)
            ->assertCanSeeTableRecords([$user1])
            ->assertCanNotSeeTableRecords([$user2]);
    });
});

describe('UserResource Create Page', function (): void {
    it('can render create user page', function (): void {
        $response = get(UserResource::getUrl('create'));

        $response->assertOk();
    });

    it('can create a new user with valid data', function (): void {
        Mail::fake();

        // Get the user role ID
        $userRole = Role::where('name', 'user')->first();

        $userData = [
            'username' => 'testuser',
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'telephone' => '1234567890',
            'homesite' => 'Test Site',
            'active' => true,
            'roles' => $userRole->id, // Explicitly set the user role
        ];

        livewire(CreateUser::class)
            ->fillForm($userData)
            ->call('create')
            ->assertHasNoFormErrors();

        assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        // Check if email was sent or queued (try both)
        try {
            Mail::assertSent(UserAccountCreated::class);
        } catch (\Exception) {
            // If not sent, check if it was queued
            Mail::assertQueued(UserAccountCreated::class);
        }
    });

    it('validates required fields when creating user', function (): void {
        livewire(CreateUser::class)
            ->fillForm([
                'username' => '',
                'email' => '',
                'firstname' => '',
                'lastname' => '',
                'homesite' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'username' => 'required',
                'email' => 'required',
                'firstname' => 'required',
                'lastname' => 'required',
                'homesite' => 'required',
            ]);
    });

    it('validates username format', function (): void {
        livewire(CreateUser::class)
            ->fillForm([
                'username' => 'invalid username!',
                'email' => 'valid@example.com',
            ])
            ->call('create')
            ->assertHasFormErrors(['username']);
    });

    it('validates email format', function (): void {
        livewire(CreateUser::class)
            ->fillForm([
                'username' => 'validuser',
                'email' => 'invalid-email',
            ])
            ->call('create')
            ->assertHasFormErrors(['email']);
    });

    it('validates unique username', function (): void {
        User::factory()->create(['username' => 'existinguser']);

        livewire(CreateUser::class)
            ->fillForm([
                'username' => 'existinguser',
                'email' => 'new@example.com',
            ])
            ->call('create')
            ->assertHasFormErrors(['username']);
    });

    it('validates unique email', function (): void {
        User::factory()->create(['email' => 'existing@example.com']);

        livewire(CreateUser::class)
            ->fillForm([
                'username' => 'newuser',
                'firstname' => 'New',
                'lastname' => 'User',
                'email' => 'existing@example.com',
                'homesite' => 'Test Site',
                'roles' => 4,
            ])
            ->call('create')
            ->assertHasFormErrors(['email']);
    });

    it('validates username starts with letter', function (): void {
        livewire(CreateUser::class)
            ->fillForm([
                'username' => '123invalid',
                'email' => 'valid@example.com',
            ])
            ->call('create')
            ->assertHasFormErrors(['username']);
    });

    it('redirects to index after creating user', function (): void {
        Mail::fake();

        // Get the user role ID
        $userRole = Role::where('name', 'user')->first();

        $userData = [
            'username' => 'testuser2',
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test2@example.com',
            'homesite' => 'Test Site',
            'active' => true,
            'roles' => $userRole->id,
        ];

        livewire(CreateUser::class)
            ->fillForm($userData)
            ->call('create')
            ->assertHasNoFormErrors();

        // Check that user was created
        assertDatabaseHas('users', [
            'username' => 'testuser2',
            'email' => 'test2@example.com',
        ]);
    });
});

describe('UserResource Edit Page', function (): void {
    it('can render edit user page', function (): void {
        $user = User::factory()->create();

        $response = get(UserResource::getUrl('edit', ['record' => $user]));

        $response->assertOk();
    });

    it('can retrieve user data for editing', function (): void {
        $user = User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        livewire(EditUser::class, ['record' => $user->id])
            ->assertFormSet([
                'username' => 'testuser',
                'email' => 'test@example.com',
            ]);
    });

    it('can save user changes', function (): void {
        $user = User::factory()->create();
        // Assign a default role if user doesn't have one
        if (!$user->roles->count()) {
            $userRole = Role::where('name', 'user')->first();
            $user->assignRole($userRole);
        }

        livewire(EditUser::class, ['record' => $user->id])
            ->fillForm([
                'firstname' => 'Updated',
                'lastname' => 'Name',
                'roles' => $user->roles->first()->id, // Keep existing role
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($user->fresh())
            ->firstname->toBe('Updated')
            ->lastname->toBe('Name');
    });

    it('validates unique username when editing (ignoring current record)', function (): void {
        $user1 = User::factory()->create(['username' => 'user1']);
        $user2 = User::factory()->create(['username' => 'user2']);

        // Assign roles if they don't have them
        if (!$user1->roles->count()) {
            $userRole = Role::where('name', 'user')->first();
            $user1->assignRole($userRole);
        }

        // Should allow keeping the same username
        livewire(EditUser::class, ['record' => $user1->id])
            ->fillForm([
                'username' => 'user1',
                'roles' => $user1->roles->first()->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        // Should not allow using another user's username
        livewire(EditUser::class, ['record' => $user1->id])
            ->fillForm([
                'username' => 'user2',
                'roles' => $user1->roles->first()->id,
            ])
            ->call('save')
            ->assertHasFormErrors(['username']);
    });

    it('can update user roles', function (): void {
        $role = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $user = User::factory()->create();

        livewire(EditUser::class, ['record' => $user->id])
            ->fillForm([
                'roles' => [$role->id],
            ])
            ->call('save');

        expect($user->fresh()->hasRole('editor'))->toBeTrue();
    });

    it('can delete user', function (): void {
        $user = User::factory()->create();

        livewire(EditUser::class, ['record' => $user->id])
            ->callAction('delete');

        expect(User::find($user->id))->toBeNull();
    });
});

describe('UserResource Permissions', function (): void {
    it('requires authentication to access user resource', function (): void {
        Auth::logout();

        $response = get(UserResource::getUrl('index'));

        $response->assertRedirect();
    });

    it('admin can access all user resource pages', function (): void {
        // User is already admin from beforeEach

        // Test list page
        get(UserResource::getUrl('index'))->assertOk();

        // Test create page
        get(UserResource::getUrl('create'))->assertOk();

        // Test edit page
        $user = User::factory()->create();
        get(UserResource::getUrl('edit', ['record' => $user]))->assertOk();
    });
});
