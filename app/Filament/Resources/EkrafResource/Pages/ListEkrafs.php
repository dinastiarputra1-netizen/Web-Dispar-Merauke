<?php

namespace App\Filament\Resources\EkrafResource\Pages;

use App\Filament\Resources\EkrafResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEkrafs extends ListRecords
{
    protected static string $resource = EkrafResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
