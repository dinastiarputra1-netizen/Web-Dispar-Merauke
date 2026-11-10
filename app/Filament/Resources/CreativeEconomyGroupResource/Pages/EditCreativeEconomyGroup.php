<?php

namespace App\Filament\Resources\CreativeEconomyGroupResource\Pages;

use App\Filament\Resources\CreativeEconomyGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreativeEconomyGroup extends EditRecord
{
    protected static string $resource = CreativeEconomyGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
