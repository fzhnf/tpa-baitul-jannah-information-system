<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentAchievementResource\Pages;
use App\Filament\Resources\StudentAchievementResource\RelationManagers;
use App\Models\StudentAchievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentAchievementResource extends Resource
{
    protected static ?string $model = StudentAchievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'id')
                    ->required(),
                Forms\Components\Select::make('class_session_id')
                    ->relationship('classSession', 'id')
                    ->required(),
                Forms\Components\Select::make('achievement_id')
                    ->relationship('achievement', 'id')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                Forms\Components\TextInput::make('keterangan'),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('makruj'),
                Forms\Components\TextInput::make('mad'),
                Forms\Components\TextInput::make('tajwid'),
                Forms\Components\TextInput::make('kelancaran'),
                Forms\Components\TextInput::make('fashohah'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classSession.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('achievement.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('makruj')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tajwid')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelancaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fashohah')
                    ->searchable(),
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
            'index' => Pages\ListStudentAchievements::route('/'),
            'create' => Pages\CreateStudentAchievement::route('/create'),
            'edit' => Pages\EditStudentAchievement::route('/{record}/edit'),
        ];
    }
}
