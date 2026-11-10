<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NationalTouristStatisticResource\Pages;
use App\Models\NationalTouristStatistic;
use App\Models\StatisticCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NationalTouristStatisticResource extends Resource
{
    protected static ?string $model = NationalTouristStatistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Data & Statistik';

    protected static ?string $navigationLabel = 'Wisatawan Nasional';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('statistic_category_id')
                    ->default(fn () => StatisticCategory::where('slug', 'wisatawan-nasional')->value('id')),

                Forms\Components\Section::make('Input Data Kunjungan Wisatawan Nasional')
                    ->schema([
                        Forms\Components\TextInput::make('year')
                            ->label('Tahun Data')
                            ->numeric()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('2026'),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('q1')->label('Triwulan I (Jan - Mar)')->numeric()->default(0)->required(),
                            Forms\Components\TextInput::make('q2')->label('Triwulan II (Apr - Jun)')->numeric()->default(0)->required(),
                            Forms\Components\TextInput::make('q3')->label('Triwulan III (Jul - Sep)')->numeric()->default(0)->required(),
                            Forms\Components\TextInput::make('q4')->label('Triwulan IV (Okt - Des)')->numeric()->default(0)->required(),
                        ]),
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

                Tables\Columns\TextColumn::make('q1')->label('Triwulan I')->numeric(),
                Tables\Columns\TextColumn::make('q2')->label('Triwulan II')->numeric(),
                Tables\Columns\TextColumn::make('q3')->label('Triwulan III')->numeric(),
                Tables\Columns\TextColumn::make('q4')->label('Triwulan IV')->numeric(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total Kunjungan')
                    ->state(fn ($record) => number_format($record->q1 + $record->q2 + $record->q3 + $record->q4, 0, ',', '.'))
                    ->weight('bold')
                    ->color('primary'),
            ])
            ->defaultSort('year', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNationalTouristStatistics::route('/'),
            'create' => Pages\CreateNationalTouristStatistic::route('/create'),
            'edit' => Pages\EditNationalTouristStatistic::route('/{record}/edit'),
        ];
    }
}
