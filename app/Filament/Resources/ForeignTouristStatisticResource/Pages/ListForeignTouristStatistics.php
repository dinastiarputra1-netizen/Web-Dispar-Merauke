<?php

namespace App\Filament\Resources\ForeignTouristStatisticResource\Pages;

use App\Filament\Resources\ForeignTouristStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListForeignTouristStatistics extends ListRecords
{
    protected static string $resource = ForeignTouristStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
