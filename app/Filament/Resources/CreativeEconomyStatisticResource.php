<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreativeEconomyStatisticResource\Pages;
use App\Models\CreativeEconomyStatistic;
use App\Models\StatisticCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CreativeEconomyStatisticResource extends Resource
{
    protected static ?string $model = CreativeEconomyStatistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Data & Statistik';

    protected static ?string $navigationLabel = 'Statistik Ekonomi Kreatif';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('statistic_category_id')
                    ->default(fn () => StatisticCategory::where('slug', 'ekonomi-kreatif')->value('id') ?? 9),

                Forms\Components\Section::make('Detail Indikator Ekonomi Kreatif')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('year')
                                ->label('Tahun Data')
                                ->numeric()
                                ->required()
                                ->default(date('Y')),

                            Forms\Components\TextInput::make('sub_category')
                                ->label('Sub Sektor / Kategori Spesifik')
                                ->placeholder('Contoh: Kuliner Khas, Kriya, Fesyen')
                                ->nullable(),
                        ]),

                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('indicator_label')
                                ->label('Nama Indikator')
                                ->required()
                                ->placeholder('Contoh: Pendapatan Omset, Jumlah Usaha'),

                            Forms\Components\TextInput::make('value')
                                ->label('Nilai Capaian')
                                ->numeric()
                                ->required(),

                            Forms\Components\TextInput::make('unit')
                                ->label('Satuan')
                                ->required()
                                ->placeholder('Contoh: Rupiah, Orang, Unit'),
                        ]),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan / Keterangan Tambahan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('sub_category')
                    ->label('Sub Sektor')
                    ->badge()
                    ->placeholder('Umum')
                    ->sortable(),

                Tables\Columns\TextColumn::make('indicator_label')
                    ->label('Indikator')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai Capaian')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => number_format($record->value, 0, ',', '.').' '.$record->unit)
                    ->color('primary')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('year', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label('Filter Tahun')
                    ->options(fn () => CreativeEconomyStatistic::pluck('year', 'year')->unique()->toArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCreativeEconomyStatistics::route('/'),
            'create' => Pages\CreateCreativeEconomyStatistic::route('/create'),
            'edit' => Pages\EditCreativeEconomyStatistic::route('/{record}/edit'),
        ];
    }
}
