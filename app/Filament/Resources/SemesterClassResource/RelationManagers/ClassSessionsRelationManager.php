<?php

namespace App\Filament\Resources\SemesterClassResource\RelationManagers;

use App\Models\ClassSession;
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('semester_class_id')
                    ->relationship('semesterClass', 'nama_semester_class')
                    ->required()
                    ->label('Class')
                    ->searchable(),
                Forms\Components\DateTimePicker::make('date')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
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
                    ->label('Class'),
                Tables\Columns\TextColumn::make('semesterClass.semester.school_year')
                    ->sortable()
                    ->searchable()
                    ->label('School Year'),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('manage')
                    ->label('Mengelola')
                    ->icon('heroicon-o-cog')
                    ->url(
                        fn(ClassSession $record): string =>
                        \App\Filament\Resources\ClassSessionResource::getUrl('manage', ['record' => $record])
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
