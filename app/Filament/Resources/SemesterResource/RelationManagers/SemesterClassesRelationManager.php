<?php

namespace App\Filament\Resources\SemesterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SemesterClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'semesterClasses';

    protected static ?string $title = 'Classes';

    protected static ?string $label = 'Class';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_semester_class')
                    ->required()
                    ->maxLength(255)
                    ->label('Name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_semester_class')
            ->columns([
                Tables\Columns\TextColumn::make('nama_semester_class')
                    ->label('Name'),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
