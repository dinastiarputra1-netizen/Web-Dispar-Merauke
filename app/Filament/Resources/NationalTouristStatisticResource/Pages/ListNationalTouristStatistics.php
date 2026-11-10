<?php

namespace App\Filament\Resources\NationalTouristStatisticResource\Pages;

use App\Filament\Resources\NationalTouristStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNationalTouristStatistics extends ListRecords
{
    protected static string $resource = NationalTouristStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
