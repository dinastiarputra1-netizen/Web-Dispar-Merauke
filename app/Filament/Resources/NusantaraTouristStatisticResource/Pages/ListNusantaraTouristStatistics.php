<?php

namespace App\Filament\Resources\NusantaraTouristStatisticResource\Pages;

use App\Filament\Resources\NusantaraTouristStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNusantaraTouristStatistics extends ListRecords
{
    protected static string $resource = NusantaraTouristStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
