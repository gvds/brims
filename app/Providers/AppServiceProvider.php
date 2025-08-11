<?php

namespace App\Providers;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(fn($user, $ability): ?true =>
            // return true;
            $user->hasRole('super_admin') ? true : null);

        TextInput::configureUsing(function (TextInput $component): void {
            $component->trim();
        });
    }
}
