<?php

namespace App\Filament\Resources\TourismForeignExchangeStatisticResource\Pages;

use App\Filament\Resources\TourismForeignExchangeStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTourismForeignExchangeStatistic extends EditRecord
{
    protected static string $resource = TourismForeignExchangeStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
