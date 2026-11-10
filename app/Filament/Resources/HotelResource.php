<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelResource\Pages;
use App\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Direktori & Akomodasi';

    protected static ?string $navigationLabel = 'Hotel & Penginapan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Hotel / Penginapan')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('type')
                ->label('Jenis Akomodasi')
                ->options([
                    'Hotel' => 'Hotel',
                    'Penginapan' => 'Penginapan',
                    'Homestay' => 'Homestay',
                ])
                ->required(),

            Forms\Components\TextInput::make('classification')
                ->label('Klasifikasi (mis. Bintang 3 / Non-Bintang)')
                ->maxLength(255),

            Forms\Components\TextInput::make('phone')
                ->label('No. Telepon / WA')
                ->tel()
                ->maxLength(255),

            Forms\Components\Textarea::make('address')
                ->label('Alamat Lengkap')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('website_url')
                ->label('URL Website')
                ->maxLength(255),

            Forms\Components\TextInput::make('maps_url')
                ->label('URL Google Maps')
                ->maxLength(255),

            Forms\Components\TextInput::make('latitude')
                ->numeric(),

            Forms\Components\TextInput::make('longitude')
                ->numeric(),

            Forms\Components\Toggle::make('is_active')
                ->label('Status Aktif')
                ->default(true)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Usaha')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Jenis')->badge(),
                Tables\Columns\TextColumn::make('classification')->label('Klasifikasi')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('Telepon')->searchable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'Hotel' => 'Hotel',
                        'Penginapan' => 'Penginapan',
                        'Homestay' => 'Homestay',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'edit' => Pages\EditHotel::route('/{record}/edit'),
        ];
    }
}
