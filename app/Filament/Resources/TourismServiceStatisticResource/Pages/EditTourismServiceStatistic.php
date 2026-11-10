<?php

namespace App\Filament\Resources\TourismServiceStatisticResource\Pages;

use App\Filament\Resources\TourismServiceStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTourismServiceStatistic extends EditRecord
{
    protected static string $resource = TourismServiceStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
