<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreativeEconomyGroupResource\Pages;
use App\Models\CreativeEconomyGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CreativeEconomyGroupResource extends Resource
{
    protected static ?string $model = CreativeEconomyGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Data & Statistik';

    protected static ?string $navigationLabel = 'Data Kelompok Ekonomi Kreatif';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('year')
                    ->label('Tahun Data')
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue((int) date('Y') + 1)
                    ->default(date('Y'))
                    ->required(),

                Forms\Components\TextInput::make('group_name')
                    ->label('Kelompok')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\Select::make('classification')
                    ->label('Label OAP/Non-OAP')
                    ->options([
                        'OAP' => 'OAP',
                        'Non-OAP' => 'Non-OAP',
                    ])
                    ->native(false)
                    ->required(),

                Forms\Components\TextInput::make('leader_source')
                    ->label('Ketua (Sumber)')
                    ->maxLength(255),

                Forms\Components\TextInput::make('subsector')
                    ->label('Subsektor')
                    ->maxLength(100),

                Forms\Components\TextInput::make('district')
                    ->label('Distrik')
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('group_name')
                    ->label('Kelompok')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classification')
                    ->label('Label OAP/Non-OAP')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'OAP' ? 'primary' : 'info'),
                Tables\Columns\TextColumn::make('leader_source')
                    ->label('Ketua (Sumber)')
                    ->searchable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('subsector')
                    ->label('Subsektor')
                    ->searchable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('district')
                    ->label('Distrik')
                    ->searchable()
                    ->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(fn (): array => CreativeEconomyGroup::query()
                        ->distinct()
                        ->orderByDesc('year')
                        ->pluck('year', 'year')
                        ->all()),
                Tables\Filters\SelectFilter::make('classification')
                    ->label('Label OAP/Non-OAP')
                    ->options([
                        'OAP' => 'OAP',
                        'Non-OAP' => 'Non-OAP',
                    ]),
            ])
            ->defaultSort('year', 'desc')
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
            'index' => Pages\ListCreativeEconomyGroups::route('/'),
            'create' => Pages\CreateCreativeEconomyGroup::route('/create'),
            'edit' => Pages\EditCreativeEconomyGroup::route('/{record}/edit'),
        ];
    }
}
