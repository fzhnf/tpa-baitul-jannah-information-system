<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemesterResource\Pages;
use App\Filament\Resources\SemesterResource\RelationManagers;
use App\Models\Semester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    protected static ?string $modelLabel = "Semester";
    protected static ?string $pluralLabel = "Semester";

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Manajemen Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('school_year')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. 2023/2024')
                    ->label('Tahun Ajaran'),
                Forms\Components\Select::make('semester_enum')
                    ->options([
                        '1' => 'Semester 1',
                        '2' => 'Semester 2',
                    ])
                    ->required()
                    ->placeholder('pilih semester')
                    ->label('Semester'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('school_year')
                    ->searchable()
                    ->sortable()
                    ->label('Tahun Ajaran'),
                Tables\Columns\TextColumn::make('semester_enum')
                    ->formatStateUsing(fn (string $state): string => "Semester $state")
                    ->sortable()
                    ->label('Semester'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Perbaharui'),
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
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
            RelationManagers\SemesterClassesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSemesters::route('/'),
            'create' => Pages\CreateSemester::route('/create'),
            'view' => Pages\ViewSemester::route('/{record}'),
            'edit' => Pages\EditSemester::route('/{record}/edit'),
        ];
    }
}
