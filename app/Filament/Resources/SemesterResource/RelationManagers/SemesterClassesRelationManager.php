<?php

namespace App\Filament\Resources\SemesterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SemesterClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'semesterClasses';

    protected static ?string $title = 'Kelas';

    protected static ?string $label = 'Kelas';

    public function form(Form $form): Form
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
                    ->label('Semester')
                    ->searchable(),
                Forms\Components\TextInput::make('nama_semester_class')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kelas'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_semester_class')
            ->columns([
                Tables\Columns\TextColumn::make('nama_semester_class')
                    ->label('Nama Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester.school_year')
                    ->sortable()
                    ->label('Tahun Ajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester.semester_enum')
                    ->formatStateUsing(fn(string $state): string => "Semester $state")
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Kelas Baru'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Perbaharui'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
