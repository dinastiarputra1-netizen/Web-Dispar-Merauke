<?php

namespace App\Filament\Resources\EkrafResource\Pages;

use App\Filament\Resources\EkrafResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEkraf extends EditRecord
{
    protected static string $resource = EkrafResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
