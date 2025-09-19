<?php

namespace App\Providers;

use App\Http\Middleware\SetUserTeam;
use App\Listeners\SetTeamOnLogin;
use Filament\Forms\Components\TextInput;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)
            ->setPermissionClass(Permission::class)
            ->setRoleClass(Role::class);

        // Livewire::addPersistentMiddleware([
        //     SetTeamOnLogin::class,
        // ]);

        Gate::before(
            fn($user, $ability): ?true => $user->hasRole('super_admin') ? true : null
        );

        TextInput::configureUsing(function (TextInput $component): void {
            $component->trim();
        });
    }
}
