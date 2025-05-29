<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $modelLabel = "Absensi";
    protected static ?string $pluralLabel = "Absensi";

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Manajemen Akademik';

    protected static ?int $navigationSort = 4; // Smaller number = higher up


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('class_session_id')
                    ->relationship('classSession', 'date', function (Builder $query) {
                        return $query->orderBy('date', 'desc');
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->semesterClass->nama_semester_class . ' - ' . $record->date->format('d M Y H:i'))
                    ->placeholder('e.g: 2025-05-03 15:00')
                    ->required()
                    ->searchable()
                    ->label('Tanggal Sesi Kelas'),
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
                    ->live() // Add live update
                    ->label('Status'),

                Forms\Components\TextInput::make('remarks')
                    ->maxLength(255)
                    ->label('Keterangan')
                    ->hidden(fn (Get $get): bool => $get('status') === 'hadir')
                    ->required(fn (Get $get): bool => in_array($get('status'), ['sakit', 'ijin', 'absen']))
                    ->helperText('Wajib diisi untuk status Sakit, Ijin, atau Absen'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success',
                        'sakit' => 'info',
                        'ijin' => 'warning',
                        'absen' => 'danger',
                    })
                    ->label('Status'),
                Tables\Columns\TextColumn::make('remarks')
                    ->label('Keterangan')
                    ->formatStateUsing(function ($state, $record) {
                        return in_array($record->status, ['sakit', 'ijin', 'absen'])
                            ? $state
                            : '-';
                    })
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false), // Make visible by default
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
