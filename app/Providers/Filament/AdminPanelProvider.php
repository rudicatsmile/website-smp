<?php

namespace App\Providers\Filament;

use App\Filament\Middleware\LogPermissionFailures;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('CMS SMP Al Wathoniyah 9')
            ->brandLogo(asset('images/logo.svg'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/favicon.png'))
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Slate,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
                'info' => Color::Sky,
            ])
            ->font('Plus Jakarta Sans')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->maxContentWidth(Width::Full)
            ->topNavigation(false)
            ->navigationGroups([
                NavigationGroup::make('Master Data')
                    ->icon('heroicon-o-circle-stack')
                    ->collapsible(),
                NavigationGroup::make('Content')
                    ->icon('heroicon-o-document-text')
                    ->collapsible(),
                NavigationGroup::make('Akademik')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsible(),
                NavigationGroup::make('Staff')
                    ->icon('heroicon-o-user-group')
                    ->collapsible(),
                NavigationGroup::make('Materi Pelajaran')
                    ->icon('heroicon-o-book-open')
                    ->collapsed(true),
                NavigationGroup::make('Komunikasi')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->collapsed(true),
                NavigationGroup::make('PPDB')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->collapsed(true),
                NavigationGroup::make('Ekstrakurikuler')
                    ->icon('heroicon-o-trophy')
                    ->collapsed(true),
                NavigationGroup::make('Event')
                    ->icon('heroicon-o-calendar-days')
                    ->collapsed(true),
                NavigationGroup::make('Alumni')
                    ->icon('heroicon-o-users')
                    ->collapsed(true),
                NavigationGroup::make('Election')
                    ->icon('heroicon-o-check-badge')
                    ->collapsed(true),
                NavigationGroup::make('Pengaturan Umum')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\RecentNews::class,
                \App\Filament\Widgets\RecentMessages::class,
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
                LogPermissionFailures::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \Awcodes\Curator\CuratorPlugin::make()
                    ->label('Media')
                    ->pluralLabel('Media Library')
                    ->navigationIcon('heroicon-o-photo')
                    ->navigationGroup('Content')
                    ->navigationSort(3)
                    ->registerNavigation(false),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                    ->navigationGroup('Pengaturan Umum')
                    ->navigationSort(2)
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
            ]);
    }
}
