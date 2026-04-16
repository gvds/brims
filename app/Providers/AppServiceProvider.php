<?php

namespace App\Providers;

use App\Enums\SystemRoles;
use App\Http\Responses\LogoutResponse;
use App\Models\Permission;
use App\Models\Role;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(PermissionRegistrar::class)
            ->setPermissionClass(Permission::class)
            ->setRoleClass(Role::class);

        // Livewire::addPersistentMiddleware([
        //     SetUserTeam::class,
        // ]);

        Gate::before(function ($user): ?bool {
            if (! $user) {
                return null;
            }

            return in_array($user->system_role, [SystemRoles::SuperAdmin]) ? true : null;
            // return in_array($user->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]) ? true : null;
        });

        // Remove leading and trailing whitespace from all TextInput components
        TextInput::configureUsing(function (TextInput $component): void {
            $component->trim();
        });
    }
}
