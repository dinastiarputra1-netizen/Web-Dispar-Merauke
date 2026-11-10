<?php

namespace App\Providers\Filament;

use App\Filament\Auth\AdminLogin;
use App\Filament\Widgets\DashboardOverview;
use App\Models\SiteSetting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminDisparPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('Admin-Dispar')
            ->path('admin')
            ->domain(config('admin.panel_domain'))
            ->login(AdminLogin::class)
            ->profile()
            ->passwordReset()
            ->brandName(fn (): string => SiteSetting::query()->value('nama_instansi') ?: 'Dinas Pariwisata dan Ekonomi Kreatif')
            ->brandLogo(function (): HtmlString {
                $siteSetting = SiteSetting::query()->first();

                return new HtmlString(view('filament.brand', [
                    'logoUrl' => $siteSetting?->logo_dinas
                        ? asset('storage/'.$siteSetting->logo_dinas)
                        : asset('images/logodisparmerauke.webp'),
                    'institutionName' => $siteSetting?->nama_instansi ?? 'Dinas Pariwisata dan Ekonomi Kreatif',
                    'districtName' => $siteSetting?->nama_daerah ?? 'Kabupaten Merauke',
                ])->render());
            })
            ->brandLogoHeight('4rem')
            ->darkMode(false)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                DashboardOverview::class,
                Widgets\AccountWidget::class,
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
            ]);
    }
}
