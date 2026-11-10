<?php

namespace App\Filament\Resources\CreativeEconomyStatisticResource\Pages;

use App\Filament\Resources\CreativeEconomyStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreativeEconomyStatistics extends ListRecords
{
    protected static string $resource = CreativeEconomyStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
