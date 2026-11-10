<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DestinationResource\Pages;
use App\Models\Destination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Publikasi & Konten';

    protected static ?string $navigationLabel = 'Ragam Wisata & Ekraf';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Destinasi / Ekraf')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $set('slug', Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->readOnly()
                            ->maxLength(255),

                        // 1. Tipe Konten Diisi Lebih Dulu & Reactive
                        Forms\Components\Select::make('type')
                            ->label('Tipe Konten')
                            ->options([
                                'wisata' => 'Destinasi Wisata',
                                'ekraf' => 'Ekonomi Kreatif',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('category_id', null)),

                        // 2. Kategori Otomatis Terfilter Sesuai Tipe Konten
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship(
                                name: 'category',
                                titleAttribute: 'name',
                                modifyQueryUsing: function (Builder $query, Get $get) {
                                    $type = $get('type');
                                    if ($type) {
                                        $query->where('type', $type);
                                    }
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get): bool => filled($get('type'))),
                    ])->columns(2),

                Forms\Components\Section::make('Lokasi & Koordinat Peta')
                    ->schema([
                        Forms\Components\TextInput::make('location_name')
                            ->label('Nama Kawasan / Kampung'),

                        Forms\Components\TextInput::make('district')
                            ->label('Distrik / Kecamatan'),

                        Forms\Components\TextInput::make('google_maps_url')
                            ->label('Tautan Google Maps (URL)')
                            ->url()
                            ->placeholder('https://maps.app.goo.gl/... atau https://goo.gl/maps/...')
                            ->helperText('Tautan navigasi titik lokasi di Google Maps (opsional jika menggunakan koordinat)')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->placeholder('-8.499110'),

                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->placeholder('140.404980'),
                    ])->columns(2),

                Forms\Components\Section::make('Konten & Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('destinations'),

                        Forms\Components\TextInput::make('youtube_url')
                            ->label('URL Video YouTube')
                            ->url()
                            ->maxLength(2048)
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->helperText('Tempel tautan video YouTube. Video akan ditampilkan di atas galeri foto.'),

                        Forms\Components\FileUpload::make('gallery_images')
                            ->label('Galeri Foto (maksimal 3)')
                            ->image()
                            ->multiple()
                            ->maxFiles(3)
                            ->reorderable()
                            ->directory('destinations/gallery')
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi Lengkap')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('published')
                            ->required(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Tanggal Terbit')
                            ->default(now()),

                        Forms\Components\Select::make('author_id')
                            ->label('Penulis')
                            ->relationship('author', 'name')
                            ->default(auth()->id()),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')->label('Gambar'),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->label('Tipe')->badge(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori'),
                Tables\Columns\TextColumn::make('district')->label('Distrik'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'wisata' => 'Wisata',
                        'ekraf' => 'Ekonomi Kreatif',
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
            'index' => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestination::route('/create'),
            'edit' => Pages\EditDestination::route('/{record}/edit'),
        ];
    }
}
