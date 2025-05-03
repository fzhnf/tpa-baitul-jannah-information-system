<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemesterClassResource\Pages;
use App\Filament\Resources\SemesterClassResource\RelationManagers;
use App\Models\SemesterClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SemesterClassResource extends Resource
{
    protected static ?string $model = SemesterClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Academic Management';

    protected static ?string $label = 'Class';

    protected static ?string $pluralLabel = 'Classes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'school_year', function (Builder $query) {
                        return $query->orderBy('school_year', 'desc')
                            ->orderBy('semester_enum', 'desc');
                    })
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->school_year} - Semester {$record->semester_enum}")
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('nama_semester_class')
                    ->required()
                    ->maxLength(255)
                    ->label('Class Name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_semester_class')
                    ->label('Class Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester.school_year')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester.semester_enum')
                    ->formatStateUsing(fn(string $state): string => "Semester $state")
                    ->sortable(),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\ClassSessionsRelationManager::class,
            RelationManagers\StudentsRelationManager::class,
            RelationManagers\TeachersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSemesterClasses::route('/'),
            'create' => Pages\CreateSemesterClass::route('/create'),
            // 'view' => Pages\ViewSemesterClass::route('/{record}'),
            'edit' => Pages\EditSemesterClass::route('/{record}/edit'),
        ];
    }
}
