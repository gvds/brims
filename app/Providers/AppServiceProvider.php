<?php

namespace App\Providers;

use App\Enums\SystemRoles;
use App\Http\Middleware\SetUserTeam;
use Filament\Forms\Components\TextInput;
use App\Models\Permission;
use App\Models\Role;
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
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
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
        //     SetUserTeam::class,
        // ]);

        Gate::before(
            fn($user): ?true => in_array($user->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]) ? true : null
        );

        // Remove leading and trailing whitespace from all TextInput components
        TextInput::configureUsing(function (TextInput $component): void {
            $component->trim();
        });
    }
}
