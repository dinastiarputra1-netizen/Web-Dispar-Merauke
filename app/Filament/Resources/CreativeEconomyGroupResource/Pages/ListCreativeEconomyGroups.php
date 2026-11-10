<?php

namespace App\Filament\Resources\CreativeEconomyGroupResource\Pages;

use App\Filament\Resources\CreativeEconomyGroupResource;
use App\Imports\CreativeEconomyGroupImporter;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListCreativeEconomyGroups extends ListRecords
{
    protected static string $resource = CreativeEconomyGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('importExcel')
                ->label('Impor Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('info')
                ->modalHeading('Impor Data Kelompok Ekonomi Kreatif')
                ->modalDescription('Kolom wajib: KELOMPOK, LABEL OAP/NON-OAP, KETUA (SUMBER), SUBSEKTOR, DISTRIK. Impor ulang pada tahun yang sama akan memperbarui nama kelompok yang sama.')
                ->form([
                    TextInput::make('year')
                        ->label('Tahun Data')
                        ->numeric()
                        ->minValue(2000)
                        ->maxValue((int) date('Y') + 1)
                        ->default(date('Y'))
                        ->required(),
                    FileUpload::make('dataFile')
                        ->label('File Excel (.xlsx)')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->maxSize(10240)
                        ->storeFiles(false)
                        ->required(),
                ])
                ->action(function (array $data, CreativeEconomyGroupImporter $importer): void {
                    $count = $importer->import($data['dataFile']->getRealPath(), (int) $data['year']);

                    Notification::make()
                        ->title('Impor selesai')
                        ->body("{$count} baris data berhasil diproses.")
                        ->success()
                        ->send();
                }),
        ];
    }
}
