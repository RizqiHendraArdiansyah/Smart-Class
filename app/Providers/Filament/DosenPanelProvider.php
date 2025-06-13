<?php

namespace App\Providers\Filament;

use App\Filament\Mahasiswa\Pages\Nilai;
use App\Filament\Pages\LoginDosen;
use App\Filament\Pages\RegisterDosen;
use App\Filament\Resources\DosenResource;
use App\Filament\Resources\KelasResource;
use App\Filament\Resources\KuisResource;
use App\Filament\Resources\MahasiswaResource;
use App\Filament\Resources\MateriResource;
use App\Filament\Resources\ModulResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\SoalResource;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Spatie\Permission\Middleware\RoleMiddleware;

class DosenPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dosen')
            ->path('dosen')
            ->login(LoginDosen::class)
            ->registration(RegisterDosen::class)
            ->spa()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\LatestQuizzes::class,
                \App\Filament\Widgets\LatestClasses::class,
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
                // RoleMiddleware::class . ':dosen'
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        NavigationItem::make()
                            ->label('Dashboard')
                            ->icon('heroicon-o-home')
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.dosen.pages.dashboard'))
                            ->url(fn (): string => Dashboard::getUrl()),
                    ])
                    ->groups([
                        // NavigationItem::make('Dashboard')
                        //     ->icon('heroicon-o-home')
                        //     ->isActiveWhen(fn(): bool => request()->routeIs('filament.dosen.pages.dashboard'))
                        //     ->url(fn(): string => Dashboard::getUrl()),
                        NavigationGroup::make('users')
                            ->label('Manajemen Data')
                            // ->icon('heroicon-o-user')
                            ->items([
                                ...DosenResource::getNavigationItems(),
                                ...KelasResource::getNavigationItems(),
                                ...MahasiswaResource::getNavigationItems(),
                                ...RoleResource::getNavigationItems(),
                            NavigationItem::make('Daftar Nilai')
                                ->icon('heroicon-o-chart-bar')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.dosen.pages.nilai'))
                                ->url(fn (): string => Nilai::getUrl()),
                            ]),
                        NavigationGroup::make('Materi')
                            ->label('Materi & Pembelajaran')
                            ->collapsible(true)
                            // ->icon('heroicon-o-user')
                            ->items([
                                ...ModulResource::getNavigationItems(),
                                ...MateriResource::getNavigationItems(),
                                // ...KuisResource::getNavigationItems(),
                                // ...SoalResource::getNavigationItems(),

                            ]),
                        NavigationGroup::make('Evaluasi')
                            ->label('Evaluasi')
                            ->collapsible(true)
                            // ->icon('heroicon-o-user')
                            ->items([
                                // ...ModulResource::getNavigationItems(),
                                // ...MateriResource::getNavigationItems(),
                                ...KuisResource::getNavigationItems(),
                                ...SoalResource::getNavigationItems(),

                            ]),
                    ]);
            })
            ->viteTheme('resources/css/filament/dosen/theme.css')
            ->plugins([
                FilamentShieldPlugin::make(),
                BreezyCore::make()
                    ->myProfile(),
                // AuthUIEnhancerPlugin::make(),
            ])
            ->font('Poppins')
            // ->theme(asset('css/filament/dosen/theme.css'))
            ->authMiddleware([
                Authenticate::class,
                RoleMiddleware::class.':dosen',
            ]);
    }
}
