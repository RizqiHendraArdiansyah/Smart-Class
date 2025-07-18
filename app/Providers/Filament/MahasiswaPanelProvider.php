<?php

namespace App\Providers\Filament;

use App\Filament\Mahasiswa\Pages\Dashboard;
use App\Filament\Mahasiswa\Pages\ForgetMahasiswa;
use App\Filament\Mahasiswa\Pages\Kelas;
use App\Filament\Mahasiswa\Pages\Kuis;
use App\Filament\Mahasiswa\Pages\LoginMahasiswa;
use App\Filament\Mahasiswa\Pages\Materi;
use App\Filament\Mahasiswa\Pages\Nilai;
use App\Filament\Mahasiswa\Pages\RegisterMahasiswa;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Spatie\Permission\Middleware\RoleMiddleware;

class MahasiswaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('mahasiswa')
            ->path('mahasiswa')
            ->login(LoginMahasiswa::class)
            ->brandName('Smart Class')
            ->registration(RegisterMahasiswa::class)
            // ->passwordReset(ForgetMahasiswa::class)
            ->spa()
            ->emailVerification()
            ->font('Poppins')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->favicon(asset('storage/gambar/logo.png'))
            ->viteTheme('resources/css/filament/mahasiswa/theme.css')
            ->discoverResources(in: app_path('Filament/Mahasiswa/Resources'), for: 'App\\Filament\\Mahasiswa\\Resources')
            ->discoverPages(in: app_path('Filament/Mahasiswa/Pages'), for: 'App\\Filament\\Mahasiswa\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Mahasiswa/Widgets'), for: 'App\\Filament\\Mahasiswa\\Widgets')
            ->widgets([
                // \App\Filament\Mahasiswa\Widgets\StatsOverview::class,
                // \App\Filament\Mahasiswa\Widgets\LatestMaterials::class,
                // \App\Filament\Mahasiswa\Widgets\TodaySchedule::class,
            ])
            ->navigation(function (\Filament\Navigation\NavigationBuilder $builder): \Filament\Navigation\NavigationBuilder {
                return $builder->items([
                    \Filament\Navigation\NavigationItem::make('Dashboard')
                        ->icon('heroicon-o-home')
                        ->isActiveWhen(fn(): bool => request()->routeIs('filament.mahasiswa.pages.dashboard'))
                        ->url(fn(): string => Dashboard::getUrl()),
                    // \Filament\Navigation\NavigationItem::make('Kelas Saya')
                    //     ->icon('heroicon-o-academic-cap')
                    //     ->isActiveWhen(fn (): bool => request()->routeIs('filament.mahasiswa.pages.kelas'))
                    //     ->url(fn (): string => Kelas::getUrl()),
                    \Filament\Navigation\NavigationItem::make('Materi Pembelajaran')
                        ->icon('heroicon-o-book-open')
                        ->isActiveWhen(fn(): bool => request()->routeIs('filament.mahasiswa.pages.materi'))
                        ->url(fn(): string => Materi::getUrl()),
                    \Filament\Navigation\NavigationItem::make('Kuis Soal')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->isActiveWhen(fn(): bool => request()->routeIs('filament.mahasiswa.pages.kuis'))
                        ->url(fn(): string => Kuis::getUrl()),
                    \Filament\Navigation\NavigationItem::make('Nilai & Hasil')
                        ->icon('heroicon-o-chart-bar')
                        ->isActiveWhen(fn(): bool => request()->routeIs('filament.mahasiswa.pages.nilai'))
                        ->url(fn(): string => Nilai::getUrl()),
                    // \Filament\Navigation\NavigationItem::make('Jadwal')
                    //     ->icon('heroicon-o-calendar')
                    //     ->url('#'),
                ]);
            })
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
                // RoleMiddleware::class . ':mahasiswa'
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile(),
                // FilamentShieldPlugin::make(),
                // AuthUIEnhancerPlugin::make(),
            ])
            // ->theme(asset('css/filament/mahasiswa/theme.css'))
            ->authMiddleware([
                Authenticate::class,
                RoleMiddleware::class . ':mahasiswa',
            ]);
    }
}
