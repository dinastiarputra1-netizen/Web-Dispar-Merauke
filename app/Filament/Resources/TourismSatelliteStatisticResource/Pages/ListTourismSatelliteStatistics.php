<?php

namespace App\Filament\Resources\TourismSatelliteStatisticResource\Pages;

use App\Filament\Resources\TourismSatelliteStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTourismSatelliteStatistics extends ListRecords
{
    protected static string $resource = TourismSatelliteStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
