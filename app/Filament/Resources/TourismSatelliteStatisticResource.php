<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TourismSatelliteStatisticResource\Pages;
use App\Models\StatisticCategory;
use App\Models\TourismSatelliteStatistic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

class TourismSatelliteStatisticResource extends Resource
{
    protected static ?string $model = TourismSatelliteStatistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-asia-australia';

    protected static ?string $navigationGroup = 'Data & Statistik';

    protected static ?string $navigationLabel = 'Statistik Satelit Pariwisata';

    protected static ?int $navigationSort = 7;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('statistic_category_id')
                    ->default(fn () => StatisticCategory::where('slug', 'satelit-pariwisata')->value('id')),

                Forms\Components\Section::make('Input Data Satelit Pariwisata')
                    ->schema([
                        Forms\Components\TextInput::make('year')
                            ->label('Tahun Data')
                            ->numeric()
                            ->required()
                            ->unique(
                                ignoreRecord: true,
                                modifyRuleUsing: fn (Unique $rule): Unique => $rule->where(
                                    'statistic_category_id',
                                    StatisticCategory::where('slug', 'satelit-pariwisata')->value('id'),
                                ),
                            )
                            ->validationMessages([
                                'unique' => 'Tahun data tersebut sudah terdaftar. Gunakan tahun lain atau edit data yang sudah ada.',
                            ])
                            ->placeholder('2027'),

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
                Tables\Columns\TextColumn::make('year')->label('Tahun')->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('q1')->label('Triwulan I')->numeric(),
                Tables\Columns\TextColumn::make('q2')->label('Triwulan II')->numeric(),
                Tables\Columns\TextColumn::make('q3')->label('Triwulan III')->numeric(),
                Tables\Columns\TextColumn::make('q4')->label('Triwulan IV')->numeric(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total Aktivitas')
                    ->state(fn (TourismSatelliteStatistic $record): string => number_format($record->q1 + $record->q2 + $record->q3 + $record->q4, 0, ',', '.'))
                    ->weight('bold')
                    ->color('primary'),
            ])
            ->defaultSort('year', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTourismSatelliteStatistics::route('/'),
            'create' => Pages\CreateTourismSatelliteStatistic::route('/create'),
            'edit' => Pages\EditTourismSatelliteStatistic::route('/{record}/edit'),
        ];
    }
}
