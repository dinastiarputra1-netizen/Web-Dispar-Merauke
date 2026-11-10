<?php

namespace App\Filament\Widgets;

use App\Models\Agenda;
use App\Models\CreativeEconomyGroup;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverview extends BaseWidget
{
    protected static ?int $sort = -4;

    protected function getStats(): array
    {
        $destinationsByType = Destination::query()
            ->where('status', 'published')
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $publishedDestinations = (int) $destinationsByType->sum();
        $publishedAgendas = Agenda::query()->where('status', 'published')->count();
        $activeHotels = Hotel::query()->where('is_active', true)->count();
        $activeRestaurants = Restaurant::query()->where('is_active', true)->count();

        $groupsByClassification = CreativeEconomyGroup::query()
            ->selectRaw('classification, COUNT(*) as total')
            ->groupBy('classification')
            ->pluck('total', 'classification');

        $draftDestinations = Destination::query()->where('status', 'draft')->count();
        $draftAgendas = Agenda::query()->where('status', 'draft')->count();

        return [
            Stat::make('Destinasi Terbit', number_format($publishedDestinations, 0, ',', '.'))
                ->description(number_format((int) ($destinationsByType['wisata'] ?? 0), 0, ',', '.').' wisata · '.number_format((int) ($destinationsByType['ekraf'] ?? 0), 0, ',', '.').' ekonomi kreatif')
                ->descriptionIcon('heroicon-m-map')
                ->color('primary')
                ->icon('heroicon-m-map')
                ->url(url('/admin/destinations')),
            Stat::make('Agenda Terbit', number_format($publishedAgendas, 0, ',', '.'))
                ->description('Agenda dan berita yang tampil di situs')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info')
                ->icon('heroicon-m-calendar-days')
                ->url(url('/admin/agendas')),
            Stat::make('Hotel Aktif', number_format($activeHotels, 0, ',', '.'))
                ->description('Hotel, penginapan, dan homestay')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success')
                ->icon('heroicon-m-building-office-2')
                ->url(url('/admin/hotels')),
            Stat::make('Restoran Aktif', number_format($activeRestaurants, 0, ',', '.'))
                ->description('Usaha kuliner dalam direktori')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('warning')
                ->icon('heroicon-m-building-storefront')
                ->url(url('/admin/restaurants')),
            Stat::make('Kelompok Ekonomi Kreatif', number_format((int) $groupsByClassification->sum(), 0, ',', '.'))
                ->description(number_format((int) ($groupsByClassification['OAP'] ?? 0), 0, ',', '.').' OAP · '.number_format((int) ($groupsByClassification['Non-OAP'] ?? 0), 0, ',', '.').' Non-OAP')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->icon('heroicon-m-user-group')
                ->url(url('/admin/creative-economy-groups')),
            Stat::make('Konten Draft', number_format($draftDestinations + $draftAgendas, 0, ',', '.'))
                ->description(number_format($draftDestinations, 0, ',', '.').' destinasi · '.number_format($draftAgendas, 0, ',', '.').' agenda')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray')
                ->icon('heroicon-m-document-text')
                ->url(url('/admin/destinations')),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}
