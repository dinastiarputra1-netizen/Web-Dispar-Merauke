<?php

namespace App\Filament\Resources\ForeignTouristStatisticResource\Pages;

use App\Filament\Resources\ForeignTouristStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditForeignTouristStatistic extends EditRecord
{
    protected static string $resource = ForeignTouristStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
