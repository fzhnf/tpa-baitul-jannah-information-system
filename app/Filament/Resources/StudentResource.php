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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Academic Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Student Name'),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('guardian')
                    ->label('Guardian (Orang tua/ wali murid)')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('entry_date'),
                Forms\Components\FileUpload::make('profile_picture_url')
                    ->image()
                    ->directory('student-profiles')
                    ->label('Profile Picture'),
                Forms\Components\TextInput::make('guardian_number')
                    ->tel()
                    ->maxLength(255)
                    ->label('Guardian Phone Number'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_name')
                    ->searchable()
                    ->sortable()
                    ->label('Student Name'),
                Tables\Columns\ImageColumn::make('profile_picture_url')
                    ->label('Profile Picture')
                    ->circular(),
                Tables\Columns\TextColumn::make('guardian')
                    ->searchable()
                    ->label('Guardian'),
                Tables\Columns\TextColumn::make('guardian_number')
                    ->label('Guardian Number'),
                Tables\Columns\TextColumn::make('entry_date')
                    ->date()
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
            // 'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
