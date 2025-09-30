<?php

namespace App\Providers;

use App\Enums\SystemRoles;
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
        //     SetTeamOnLogin::class,
        // ]);
        Gate::before(
            fn($user): ?true => $user->system_role == SystemRoles::SuperAdmin ? true : null
        );

        TextInput::configureUsing(function (TextInput $component): void {
            $component->trim();
        });
    }
}
