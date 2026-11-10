<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NusantaraTouristStatisticResource\Pages;
use App\Models\NusantaraTouristStatistic;
use App\Models\StatisticCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NusantaraTouristStatisticResource extends Resource
{
    protected static ?string $model = NusantaraTouristStatistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Data & Statistik';

    protected static ?string $navigationLabel = 'Wisatawan Nusantara';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Terhubung otomatis dengan kategori 'wisatawan-nusantara'
                Forms\Components\Hidden::make('statistic_category_id')
                    ->default(fn () => StatisticCategory::where('slug', 'wisatawan-nusantara')->value('id')),

                Forms\Components\Section::make('Input Data Kunjungan Pertriwulan')
                    ->schema([
                        Forms\Components\TextInput::make('year')
                            ->label('Tahun Data')
                            ->numeric()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('2026'),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('q1')
                                ->label('Triwulan I (Jan - Mar)')
                                ->numeric()
                                ->default(0)
                                ->required(),

                            Forms\Components\TextInput::make('q2')
                                ->label('Triwulan II (Apr - Jun)')
                                ->numeric()
                                ->default(0)
                                ->required(),

                            Forms\Components\TextInput::make('q3')
                                ->label('Triwulan III (Jul - Sep)')
                                ->numeric()
                                ->default(0)
                                ->required(),

                            Forms\Components\TextInput::make('q4')
                                ->label('Triwulan IV (Okt - Des)')
                                ->numeric()
                                ->default(0)
                                ->required(),
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

                Tables\Columns\TextColumn::make('q1')
                    ->label('Triwulan I')
                    ->numeric(),

                Tables\Columns\TextColumn::make('q2')
                    ->label('Triwulan II')
                    ->numeric(),

                Tables\Columns\TextColumn::make('q3')
                    ->label('Triwulan III')
                    ->numeric(),

                Tables\Columns\TextColumn::make('q4')
                    ->label('Triwulan IV')
                    ->numeric(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total Kunjungan')
                    ->state(fn ($record) => number_format($record->q1 + $record->q2 + $record->q3 + $record->q4, 0, ',', '.'))
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('year', 'desc')
            ->filters([
                //
            ])
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
            'index' => Pages\ListNusantaraTouristStatistics::route('/'),
            'create' => Pages\CreateNusantaraTouristStatistic::route('/create'),
            'edit' => Pages\EditNusantaraTouristStatistic::route('/{record}/edit'),
        ];
    }
}
