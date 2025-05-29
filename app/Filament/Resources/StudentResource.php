<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $modelLabel = "Murid";
    protected static ?string $pluralLabel = "Murid";

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Manajemen Akademik';

    protected static ?int $navigationSort = 3; // Smaller number = higher up

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Murid'),
                Forms\Components\TextInput::make('address')
                    ->label('Alamat')
                    ->maxLength(255),
                Forms\Components\TextInput::make('guardian')
                    ->label('Orang tua/ wali murid')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('entry_date')
                    ->label('Tanggal masuk'),
                Forms\Components\FileUpload::make('profile_picture_url')
                    ->image()
                    ->directory('student-profiles')
                    ->label('Foto profil'),
                Forms\Components\TextInput::make('guardian_number')
                    ->tel()
                    ->maxLength(255)
                    ->label('Nomor telepon orang tua/ wali murid'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Murid'),
                Tables\Columns\ImageColumn::make('profile_picture_url')
                    ->label('Foto Profile')
                    ->circular(),
                Tables\Columns\TextColumn::make('guardian')
                    ->searchable()
                    ->label('Orang Tua / Wali Murid'),
                Tables\Columns\TextColumn::make('guardian_number')
                    ->label('Nomor Orang Tua / Wali Murid'),
                Tables\Columns\TextColumn::make('entry_date')
                    ->date()
                    ->label('Tanggal Masuk')
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
            RelationManagers\AttendancesRelationManager::class,
            RelationManagers\StudentAchievementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
