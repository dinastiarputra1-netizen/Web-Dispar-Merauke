<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantResource\Pages;
use App\Models\Restaurant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Direktori & Akomodasi';

    protected static ?string $navigationLabel = 'Restoran & Kuliner';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('business_name')
                    ->label('Nama Kegiatan Usaha')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('business_type')
                    ->label('Jenis Usaha')
                    ->options([
                        'Restaurant/Rumah Makan' => 'Restaurant/Rumah Makan',
                        'Jasa Boga' => 'Jasa Boga',
                        'Cafe' => 'Cafe',
                        'Bar' => 'Bar',
                        'Karaoke' => 'Karaoke',
                        'Kelab Malam' => 'Kelab Malam',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('license_number')
                    ->label('No. Izin Usaha')
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->label('Alamat Lengkap')
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->required(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_name')->label('Nama Usaha')->searchable(),
                Tables\Columns\TextColumn::make('business_type')->label('Jenis Usaha')->badge(),
                Tables\Columns\TextColumn::make('license_number')->label('No. Izin Usaha')->searchable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('business_type')
                    ->options([
                        'Restaurant/Rumah Makan' => 'Restaurant/Rumah Makan',
                        'Jasa Boga' => 'Jasa Boga',
                        'Cafe' => 'Cafe',
                        'Bar' => 'Bar',
                        'Karaoke' => 'Karaoke',
                        'Kelab Malam' => 'Kelab Malam',
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
            'index' => Pages\ListRestaurants::route('/'),
            'create' => Pages\CreateRestaurant::route('/create'),
            'edit' => Pages\EditRestaurant::route('/{record}/edit'),
        ];
    }
}
