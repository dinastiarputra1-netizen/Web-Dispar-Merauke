<?php

namespace App\Filament\Resources\StatisticEntryResource\Pages;

use App\Filament\Resources\StatisticEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatisticEntries extends ListRecords
{
    protected static string $resource = StatisticEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
