<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\EditProfile;
use App\Filament\Pages\Login;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    #[\Override]
    public function register(): void
    {
        parent::register();

        FilamentView::registerRenderHook(
            name: 'panels::head.end',
            hook: fn(): string => Blade::render(string: "@vite('resources/js/app.js')"),
        );
    }

    // public function boot(): void
    // {
    //     FilamentColor::register([
    //         'indigo' => Color::Indigo,
    //     ]);
    // }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->renderHook(
                PanelsRenderHook::PAGE_START,
                function (): void {
                    if (session()->has('currentProject')) {
                        session()->forget('currentProject');
                    }
                }
            )
            ->id('app')
            ->path('')
            ->login(Login::class)
            ->plugins([])
            ->multiFactorAuthentication([
                AppAuthentication::make()
                    ->recoverable(),
            ])
            ->profile(EditProfile::class)
            ->passwordReset()
            ->globalSearch(false)
            ->colors([
                'primary' => Color::Sky,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationItems([
                NavigationItem::make('Team')
                    ->url(fn(): string => route('filament.app.resources.teams.view', [
                        'record' => Auth::user()->team_id ?? '',
                    ]))
                    ->icon('heroicon-o-user-group')
                    ->sort(2),
                NavigationItem::make('Admin')
                    ->url('/admin')
                    ->icon('heroicon-o-wrench')
                    ->sort(10)
                    ->visible(fn(): bool => Auth::user()->canAccessPanel(Filament::getPanel('admin'))),
            ])
            ->databaseNotifications()
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            // ->widgets([
            // AccountWidget::class,
            // FilamentInfoWidget::class,
            // ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->resourceCreatePageRedirect('index')
            ->resourceEditPageRedirect('index')
            ->maxContentWidth('full')
            ->sidebarCollapsibleOnDesktop()
            ->spa()
            ->unsavedChangesAlerts()
            ->sidebarWidth('15rem');
    }
}
