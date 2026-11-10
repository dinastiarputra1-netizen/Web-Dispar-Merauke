<?php

namespace App\Filament\Resources\StatisticCategoryResource\Pages;

use App\Filament\Resources\StatisticCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatisticCategory extends EditRecord
{
    protected static string $resource = StatisticCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
