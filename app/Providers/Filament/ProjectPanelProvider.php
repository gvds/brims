<?php

namespace App\Providers\Filament;

use App\Filament\Project\Pages\Dashboard;
use App\Models\Project;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant;
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
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ProjectPanelProvider extends PanelProvider
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

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->renderHook(
                PanelsRenderHook::TOPBAR_LOGO_AFTER,
                function () {
                    $currentProject = session()->has('currentProject') ? session('currentProject') : null;

                    return view('filament.topbar', ['currentProject' => $currentProject]);
                }
            )
            ->id('project')
            ->path('project')
            ->tenant(Project::class)
            ->tenantMenu(false)
            ->tenantMiddleware([
                SyncShieldTenant::class,
            ], isPersistent: true)
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Authorisation'),
                // ->registerNavigation(1),
            ])
            ->globalSearch(false)
            ->colors([
                'primary' => Color::Violet,
            ])
            ->breadcrumbs(false)
            ->discoverResources(in: app_path('Filament/Project/Resources'), for: 'App\Filament\Project\Resources')
            ->discoverPages(in: app_path('Filament/Project/Pages'), for: 'App\Filament\Project\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationItems([
                NavigationItem::make('Main Panel')
                    ->url('/')
                    ->icon('heroicon-o-home')
                    ->sort(0),
                NavigationItem::make('Project')
                    ->url(fn(): string => route('filament.project.resources.projects.view', [
                        'tenant' => Filament::getTenant(),
                        'record' => Filament::getTenant(),
                    ]))
                    ->icon('heroicon-o-rectangle-stack')
                    ->sort(1)
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.project.resources.projects.*')),
                NavigationItem::make('Generate Schedule')
                    ->url('/schedule/thisweek', $shouldOpenInNewTab = true)
                    ->icon('heroicon-o-calendar')
                    ->sort(3),
            ])
            ->databaseNotifications()
            ->discoverWidgets(in: app_path('Filament/Project/Widgets'), for: 'App\Filament\Project\Widgets')
            // ->widgets([
            //     AccountWidget::class,
            //     FilamentInfoWidget::class,
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
            ->spaUrlExceptions([
                '*/',
                '*/admin*',
            ])
            ->sidebarWidth('15rem');
    }
}
