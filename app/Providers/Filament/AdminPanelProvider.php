<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetLocale;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Build Diary')
            ->favicon('/favicon/favicon.svg')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->darkMode(true)
            ->renderHook(
                'panels::head.start',
                fn () => Blade::render(<<<'BLADE'
                    <style>
                        /* Hide Filament theme toggle - controlled via Preferences page */
                        [x-data*="theme"] { display: none !important; }
                    </style>
                    <script>
                        (function() {
                            const theme = @json(Auth::check() ? Auth::user()->getTheme() : 'system');

                            // Sync localStorage with user's DB preference
                            if (theme === 'dark') {
                                localStorage.setItem('theme', 'dark');
                                document.documentElement.classList.add('dark');
                            } else if (theme === 'light') {
                                localStorage.setItem('theme', 'light');
                                document.documentElement.classList.remove('dark');
                            } else {
                                // System preference
                                localStorage.removeItem('theme');
                                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                                    document.documentElement.classList.add('dark');
                                }
                            }
                        })();
                    </script>
                BLADE)
            )
            ->navigationGroups([
                NavigationGroup::make(__('app.navigation.projects')),
                NavigationGroup::make(__('app.navigation.workshop')),
                NavigationGroup::make(__('app.navigation.people')),
                NavigationGroup::make(__('app.navigation.settings')),
            ])
            ->plugins([
                FilamentFullCalendarPlugin::make()
                    ->selectable()
                    ->editable(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\RecentProjects::class,
                \App\Filament\Widgets\UpcomingBirthdays::class,
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
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
