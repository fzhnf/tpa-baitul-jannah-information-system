<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassSessionResource\Pages;
use App\Filament\Resources\ClassSessionResource\RelationManagers;
use App\Models\ClassSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClassSessionResource extends Resource
{
    protected static ?string $model = ClassSession::class;

    protected static ?string $modelLabel = "Sesi Kelas";
    protected static ?string $pluralLabel = "Sesi Kelas";

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Manajemen Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('semester_class_id')
                    ->relationship('semesterClass', 'nama_semester_class')
                    ->required()
                    ->label('Kelas')
                    ->searchable(),
                Forms\Components\DateTimePicker::make('date')
                    ->required()
                    ->label('Tanggal & Waktu'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semesterClass.nama_semester_class')
                    ->sortable()
                    ->searchable()
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('semesterClass.semester.school_year')
                    ->sortable()
                    ->searchable()
                    ->label('Tahun Ajaran'),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal & Waktu'),
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
                    ->label('Liat'),
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
            RelationManagers\AttendancesRelationManager::class,
            RelationManagers\StudentAchievementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassSessions::route('/'),
            'create' => Pages\CreateClassSession::route('/create'),
            'view' => Pages\ViewClassSession::route('/{record}'),
            'edit' => Pages\EditClassSession::route('/{record}/edit'),
            'manage' => Pages\ManageClassSession::route('/{record}/manage'),
        ];
    }
}
