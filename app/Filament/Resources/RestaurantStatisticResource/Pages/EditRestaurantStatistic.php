<?php

namespace App\Filament\Resources\RestaurantStatisticResource\Pages;

use App\Filament\Resources\RestaurantStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRestaurantStatistic extends EditRecord
{
    protected static string $resource = RestaurantStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
