<?php

namespace App\Filament\Resources\NusantaraTouristStatisticResource\Pages;

use App\Filament\Resources\NusantaraTouristStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNusantaraTouristStatistic extends EditRecord
{
    protected static string $resource = NusantaraTouristStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
