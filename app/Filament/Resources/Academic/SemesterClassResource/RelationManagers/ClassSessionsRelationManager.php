<?php

namespace App\Filament\Resources\Academic\SemesterClassResource\RelationManagers;

use App\Models\Academic\ClassSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassSessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'classSessions';

    protected static ?string $title = 'Sesi Kelas';

    public function form(Form $form): Form
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
                    ->label('Tanggal'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Deskripsi'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
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
                    ->label('Tanggal'),
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
                    ->label('Sesi Baru'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Perbaharui'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
                Tables\Actions\Action::make('manage')
                    ->label('Mengelola')
                    ->icon('heroicon-o-cog')
                    ->url(
                        fn (ClassSession $record): string =>
                        \App\Filament\Resources\Academic\ClassSessionResource::getUrl('manage', ['record' => $record])
                    )
                    ->color('success'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
