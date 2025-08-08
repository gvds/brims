<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ProjectPanelProvider extends PanelProvider
{
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
                    ->url('/projects')
                    ->icon('heroicon-o-home')
            ])
            ->discoverWidgets(in: app_path('Filament/Project/Widgets'), for: 'App\Filament\Project\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
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
            ->sidebarWidth('15rem');
    }
}
