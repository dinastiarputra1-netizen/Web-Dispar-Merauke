<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatisticEntryResource\Pages;
use App\Models\Category;
use App\Models\StatisticEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str; // <-- PENTING: Tambahkan ini untuk Masking Angka

class StatisticEntryResource extends Resource
{
    protected static ?string $model = StatisticEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Statistik Pariwisata';

    protected static ?string $navigationLabel = 'Data Input Statistik';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    /**
     * Pemetaan Rekomendasi Indikator & Satuan per Kategori
     */
    public static function getIndicatorMapping(?string $categoryId): array
    {
        if (! $categoryId) {
            return ['indicator' => null, 'unit' => null];
        }

        $category = Category::find($categoryId);
        if (! $category) {
            return ['indicator' => null, 'unit' => null];
        }

        $name = Str::lower(trim($category->name));

        if (Str::contains($name, 'akomodasi') || Str::contains($name, 'hotel') || Str::contains($name, 'penginapan')) {
            return ['indicator' => 'Tingkat Penghunian Kamar (TPK)', 'unit' => '%'];
        }
        if (Str::contains($name, 'devisa')) {
            return ['indicator' => 'Total Penerimaan Devisa Pariwisata', 'unit' => 'Rupiah'];
        }
        if (Str::contains($name, 'ekonomi') || Str::contains($name, 'kreatif') || Str::contains($name, 'ekraf')) {
            return ['indicator' => 'Total Omset Usaha Ekraf', 'unit' => 'Rupiah'];
        }
        if (Str::contains($name, 'jasa') || Str::contains($name, 'perjalanan') || Str::contains($name, 'biro')) {
            return ['indicator' => 'Jumlah Biro Perjalanan Wisata (BPW)', 'unit' => 'Unit Usaha'];
        }
        if (Str::contains($name, 'satelit')) {
            return ['indicator' => 'Kontribusi Pariwisata Terhadap PDRB', 'unit' => '%'];
        }
        if (Str::contains($name, 'restoran') || Str::contains($name, 'kuliner')) {
            return ['indicator' => 'Jumlah Usaha Restoran & Kuliner', 'unit' => 'Unit Usaha'];
        }
        if (Str::contains($name, 'mancanegara')) {
            return ['indicator' => 'Jumlah Kunjungan Wisatawan Mancanegara', 'unit' => 'Orang'];
        }
        if (Str::contains($name, 'nasional')) {
            return ['indicator' => 'Jumlah Perjalanan Wisatawan Nasional', 'unit' => 'Perjalanan'];
        }
        if (Str::contains($name, 'nusantara')) {
            return ['indicator' => 'Jumlah Kunjungan Wisatawan Nusantara', 'unit' => 'Orang'];
        }

        return ['indicator' => $category->name, 'unit' => 'Unit Usaha'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. Pilih Kategori Utama
                Forms\Components\Select::make('statistic_category_id')
                    ->label('Kategori Statistik')
                    ->relationship('category', 'name')
                    ->placeholder('Pilih Kategori Statistik')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                        $set('sub_category', null);

                        $mapping = static::getIndicatorMapping($state);
                        if ($mapping['indicator']) {
                            $set('indicator_label', $mapping['indicator']);
                        }
                        if ($mapping['unit']) {
                            $set('unit', $mapping['unit']);
                        }
                    }),

                // 2. Pilih Jenis / Subsektor
                Forms\Components\Select::make('sub_category')
                    ->label('Jenis / Subsektor')
                    ->placeholder('Pilih Jenis / Subsektor')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->options(function (Get $get) {
                        $categoryId = $get('statistic_category_id');
                        if (! $categoryId) {
                            return [];
                        }

                        $category = Category::find($categoryId);
                        if (! $category) {
                            return [];
                        }

                        if (Str::contains(strtolower($category->name), 'ekonomi kreatif') || $category->type === 'ekraf') {
                            return [
                                'Kuliner Khas' => 'Kuliner Khas',
                                'Kerajinan Tangan' => 'Kerajinan Tangan',
                                'Fesyen & Kriya' => 'Fesyen & Kriya',
                                'Seni Pertunjukan' => 'Seni Pertunjukan',
                                'Musik & Seni Musik' => 'Musik & Seni Musik',
                                'Fotografi & Video' => 'Fotografi & Video',
                                'Desain Grafis' => 'Desain Grafis',
                                'Lainnya' => 'Lainnya',
                            ];
                        }

                        return [
                            'Wisata Alam' => 'Wisata Alam',
                            'Wisata Budaya & Sejarah' => 'Wisata Budaya & Sejarah',
                            'Wisata Buatan' => 'Wisata Buatan',
                            'Akomodasi & Perhotelan' => 'Akomodasi & Perhotelan',
                            'Umum / Makro' => 'Umum / Makro',
                        ];
                    })
                    ->required(fn (Get $get): bool => filled($get('statistic_category_id'))),

                Forms\Components\TextInput::make('year')
                    ->label('Tahun')
                    ->required()
                    ->numeric()
                    ->default(date('Y')),

                // Dropdown Bulan
                Forms\Components\Select::make('month')
                    ->label('Bulan (Opsional)')
                    ->placeholder('Pilih Bulan (Opsional)')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->options([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ]),

                // 3. Indikator / Uraian
                Forms\Components\TextInput::make('indicator_label')
                    ->label('Indikator / Uraian')
                    ->placeholder('Masukkan atau sesuaikan nama indikator')
                    ->helperText('Dapat Anda ubah atau ketik kustom secara bebas.')
                    ->required()
                    ->maxLength(255),

                // 4. Nilai / Angka (Menggunakan Filament Native Masking & Stripping)
                Forms\Components\TextInput::make('value')
                    ->label('Nilai / Angka')
                    ->placeholder('Contoh: 1.000.000')
                    ->required()
                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                    ->stripCharacters('.')
                    ->numeric(),

                // 5. Satuan
                Forms\Components\TextInput::make('unit')
                    ->label('Satuan')
                    ->placeholder('Misal: Orang, Rupiah, Unit Usaha, %')
                    ->helperText('Dapat Anda ubah atau ketik kustom secara bebas.')
                    ->maxLength(255),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan Tambahan')
                    ->placeholder('Tambahkan catatan pendukung jika ada (opsional)')
                    ->columnSpanFull(),

                Forms\Components\Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')->label('Kategori')->sortable(),
                Tables\Columns\TextColumn::make('sub_category')->label('Jenis / Subsektor')->badge()->color('info'),
                Tables\Columns\TextColumn::make('year')->label('Tahun')->sortable(),
                Tables\Columns\TextColumn::make('indicator_label')->label('Indikator')->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: ',',
                        thousandsSeparator: '.'
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')->label('Satuan'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statistic_category_id')
                    ->label('Filter Kategori')
                    ->relationship('category', 'name')
                    ->native(false),
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
            'index' => Pages\ListStatisticEntries::route('/'),
            'create' => Pages\CreateStatisticEntry::route('/create'),
            'edit' => Pages\EditStatisticEntry::route('/{record}/edit'),
        ];
    }
}
