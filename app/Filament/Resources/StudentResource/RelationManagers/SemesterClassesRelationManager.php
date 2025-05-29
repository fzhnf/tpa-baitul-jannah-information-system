<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use App\Models\SemesterClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SemesterClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'semesterClasses';

    protected static ?string $title = 'Kelas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'school_year', function (Builder $query) {
                        return $query->orderBy('school_year', 'desc')
                            ->orderBy('semester_enum', 'desc');
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->school_year} - Semester {$record->semester_enum}")
                    ->required()
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
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('nama_semester_class')
                    ->label('Nama Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester.school_year')
                    ->sortable()
                    ->label('Tahun Ajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester.semester_enum')
                    ->formatStateUsing(fn (string $state): string => "Semester $state")
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Kelas Baru'),
                Tables\Actions\AttachAction::make()
                    ->label('Masukkan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Perbaharui'),
                Tables\Actions\DetachAction::make()
                    ->label('Keluarkan'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
                Tables\Actions\Action::make('view_progress')
                    ->label('Lihat Progress')
                    ->icon('heroicon-o-chart-bar-square')
                    ->color('success')
                    ->url(function (SemesterClass $record) {
                        return route('filament.admin.resources.semester-classes.student-progression', [
                            'semesterClass' => $record->id,
                            'student' => $this->ownerRecord->id,
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
