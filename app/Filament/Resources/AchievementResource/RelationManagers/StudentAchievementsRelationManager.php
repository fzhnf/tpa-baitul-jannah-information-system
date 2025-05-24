<?php

namespace App\Filament\Resources\AchievementResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentAchievementsRelationManager extends RelationManager
{
    protected static string $relationship = 'studentAchievements';

    protected static ?string $title = 'Pencapaian Murid';

    public function form(Form $form): Form
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
                ->searchable()
                ->label('Murid'),

            Forms\Components\Select::make('class_session_id')
                ->relationship('classSession', 'date', function (Builder $query) {
                    return $query->orderBy('date', 'desc');
                })
                ->getOptionLabelFromRecordUsing(
                    fn($record) =>
                    $record->semesterClass->nama_semester_class . ' - ' . $record->date->format('d M Y H:i')
                )
                ->label('Sesi Kelas')
                ->placeholder('e.g: 2025-05-03 15:00')
                ->required()
                ->searchable(),

            Forms\Components\DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal'),

            Forms\Components\Select::make('achievement_id')
                ->relationship('achievement', 'achievement_name')
                ->required()
                ->searchable()
                ->label('Pencapaian'),

            Forms\Components\TextInput::make('keterangan')
                ->maxLength(255)
                ->label('Deskripsi'),

            Forms\Components\Textarea::make('catatan')
                ->maxLength(65535)
                ->label('Catatan'),

            Forms\Components\Section::make('Evaluasi')->schema([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('student.student_name')
                    ->label('Murid')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('classSession.date')
                    ->label('Tanggal Kelas Sesi')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('achievement.achievement_name')
                    ->label('Pencapaian')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Deskirpsi')
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
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Pencapaian Murid Baru'),
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
