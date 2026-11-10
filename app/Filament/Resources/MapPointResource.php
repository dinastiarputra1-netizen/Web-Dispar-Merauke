<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MapPointResource\Pages;
use App\Models\MapPoint;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MapPointResource extends Resource
{
    protected static ?string $model = MapPoint::class;

    // Konfigurasi Navigation Menu
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Publikasi & Konten';

    protected static ?string $navigationLabel = 'Peta Sebaran Wisata';

    protected static ?string $modelLabel = 'Titik Wisata';

    protected static ?string $pluralModelLabel = 'Peta Sebaran Wisata';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('title')
                        ->label('Nama Destinasi Wisata')
                        ->placeholder('Contoh: Taman Nasional Wasur')
                        ->required()
                        ->maxLength(255),

                    Select::make('distrik')
                        ->label('Distrik')
                        ->options([
                            'Merauke' => 'Distrik Merauke',
                            'Semangga' => 'Distrik Semangga',
                            'Tanah Miring' => 'Distrik Tanah Miring',
                            'Kurik' => 'Distrik Kurik',
                            'Sota' => 'Distrik Sota',
                            'Naukenjerai' => 'Distrik Naukenjerai',
                            'Malind' => 'Distrik Malind',
                            'Okaba' => 'Distrik Okaba',
                            'Kimam' => 'Distrik Kimam',
                            'Tubang' => 'Distrik Tubang',
                            'Muting' => 'Distrik Muting',
                            'Jagebob' => 'Distrik Jagebob',
                            'Elikobel' => 'Distrik Elikobel',
                        ])
                        ->searchable()
                        ->required(),

                    Select::make('kategori')
                        ->label('Jenis Wisata')
                        ->options([
                            'Wisata' => 'Destinasi Wisata (Umum)',
                            'Wisata Alam' => 'Wisata Alam & Hutan',
                            'Wisata Bahari' => 'Wisata Bahari & Pesisir',
                            'Wisata Budaya' => 'Wisata Budaya & Sejarah',
                            'Agrowisata' => 'Agrowisata & Ekowisata',
                        ])
                        ->default('Wisata')
                        ->required(),

                    TextInput::make('link_postingan')
                        ->label('Tautan Detail Wisata (URL)')
                        ->placeholder('/publikasi/ragam-wisata/taman-nasional-wasur')
                        ->helperText('URL ke halaman artikel/ragam wisata terkait saat pin diklik')
                        ->maxLength(255),
                ]),

                // Custom Native Leaflet Map Picker Component
                ViewField::make('location_picker')
                    ->label('Tentukan Posisi Pin di Gambar Peta')
                    ->view('filament.forms.components.range-map-picker')
                    ->columnSpanFull(),

                Grid::make(2)->schema([
                    TextInput::make('latitude')
                        ->label('Koordinat Y (Latitude)')
                        ->numeric()
                        ->required()
                        ->readOnly(),

                    TextInput::make('longitude')
                        ->label('Koordinat X (Longitude)')
                        ->numeric()
                        ->required()
                        ->readOnly(),
                ]),

                Textarea::make('description')
                    ->label('Ringkasan / Keterangan Wisata')
                    ->rows(3)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Tampilkan di Peta Publik')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Nama Destinasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('distrik')
                    ->label('Distrik')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kategori')
                    ->label('Jenis Wisata')
                    ->badge()
                    ->color('success'),

                TextColumn::make('latitude')
                    ->label('Koordinat (Y, X)')
                    ->formatStateUsing(fn ($record) => "{$record->latitude}, {$record->longitude}")
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('distrik')
                    ->label('Filter Distrik'),
                SelectFilter::make('kategori')
                    ->label('Filter Jenis Wisata'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMapPoints::route('/'),
            'create' => Pages\CreateMapPoint::route('/create'),
            'edit' => Pages\EditMapPoint::route('/{record}/edit'),
        ];
    }
}
