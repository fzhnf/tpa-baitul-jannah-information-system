<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentAchievementResource\Pages;
use App\Models\StudentAchievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentAchievementResource extends Resource
{
    protected static ?string $model = StudentAchievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Academic Management';
    protected static ?string $label = 'Student Achievement';

    public static function form(Form $form): Form
    {
        $gradeOptions = [
            '-' => '-',
            'A' => 'A',
            'B+' => 'B+',
            'B' => 'B',
            'B-' => 'B-',
            'C' => 'C',
        ];

        return $form->schema([
            Forms\Components\Select::make('student_id')
                ->relationship('student', 'student_name')
                ->required()
                ->searchable(),

            Forms\Components\Select::make('class_session_id')
                ->relationship('classSession', 'date', function (Builder $query) {
                    return $query->orderBy('date', 'desc');
                })
                ->getOptionLabelFromRecordUsing(
                    fn($record) =>
                    $record->semesterClass->nama_semester_class . ' - ' . $record->date->format('d M Y H:i')
                )
                ->label('Class Session')
                ->required()
                ->searchable(),

            Forms\Components\DatePicker::make('tanggal')
                ->required()
                ->label('Date'),

            Forms\Components\Select::make('achievement_id')
                ->relationship('achievement', 'achievement_name')
                ->required()
                ->searchable(),

            Forms\Components\TextInput::make('keterangan')
                ->maxLength(255)
                ->label('Description'),

            Forms\Components\Textarea::make('catatan')
                ->maxLength(65535)
                ->label('Notes'),

            Forms\Components\Section::make('Evaluation')->schema([
                Forms\Components\Select::make('makruj')
                    ->options($gradeOptions)
                    ->default('-'),

                Forms\Components\Select::make('mad')
                    ->options($gradeOptions)
                    ->default('-'),

                Forms\Components\Select::make('tajwid')
                    ->options($gradeOptions)
                    ->default('-'),

                Forms\Components\Select::make('kelancaran')
                    ->options($gradeOptions)
                    ->default('-'),

                Forms\Components\Select::make('fashohah')
                    ->options($gradeOptions)
                    ->default('-'),
            ])->columns(5),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.student_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('classSession.date')
                    ->label('Class Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('achievement.achievement_name')
                    ->label('Achievement')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Description')
                    ->searchable(),

                Tables\Columns\TextColumn::make('makruj')->sortable(),
                Tables\Columns\TextColumn::make('mad')->sortable(),
                Tables\Columns\TextColumn::make('tajwid')->sortable(),
                Tables\Columns\TextColumn::make('kelancaran')->sortable(),
                Tables\Columns\TextColumn::make('fashohah')->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
