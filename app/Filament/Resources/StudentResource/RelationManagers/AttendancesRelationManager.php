<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('class_session_id')
                    ->relationship('classSession', 'date', function (Builder $query) {
                        return $query->orderBy('date', 'desc');
                    })
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->semesterClass->nama_semester_class . ' - ' . $record->date->format('d M Y H:i'))
                    ->placeholder('e.g: 2025-05-03 15:00')
                    ->required()
                    ->searchable()
                    ->label('Class Session (Date)'),
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'student_name')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'sakit' => 'Sakit',
                        'ijin' => 'Ijin',
                        'absen' => 'Absen',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('remarks')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('classSession.semesterClass.nama_semester_class')
                    ->sortable()
                    ->searchable()
                    ->label('Class'),
                Tables\Columns\TextColumn::make('classSession.date')
                    ->dateTime()
                    ->sortable()
                    ->label('Session Date'),
                Tables\Columns\TextColumn::make('student.student_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'hadir' => 'success',
                        'sakit' => 'info',
                        'ijin' => 'warning',
                        'absen' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('remarks')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\CreateAction::make(),
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
}
