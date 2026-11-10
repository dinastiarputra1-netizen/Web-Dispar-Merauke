<?php

namespace App\Filament\Resources\CreativeEconomyStatisticResource\Pages;

use App\Filament\Resources\CreativeEconomyStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreativeEconomyStatistic extends EditRecord
{
    protected static string $resource = CreativeEconomyStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
