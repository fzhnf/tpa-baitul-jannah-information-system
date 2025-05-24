<?php

namespace App\Filament\Resources\ClassSessionResource\RelationManagers;

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

    protected static ?string $title = 'Absensi';

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
                    ->label('Sesi Kelas'),
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'student_name')
                    ->required()
                    ->searchable()
                    ->label('Murid'),
                Forms\Components\Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'sakit' => 'Sakit',
                        'ijin' => 'Ijin',
                        'absen' => 'Absen',
                    ])
                    ->required()
                    ->label('Status'),
                Forms\Components\TextInput::make('remarks')
                    ->maxLength(255)
                    ->label('Keterangan'),
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
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('classSession.date')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Sesi'),
                Tables\Columns\TextColumn::make('student.student_name')
                    ->sortable()
                    ->searchable()
                    ->label('Nama Murid'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'hadir' => 'success',
                        'sakit' => 'info',
                        'ijin' => 'warning',
                        'absen' => 'danger',
                    })
                    ->label('Status'),
                Tables\Columns\TextColumn::make('remarks')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Keterangan'),
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
                    ->label('Absensi baru'),
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
