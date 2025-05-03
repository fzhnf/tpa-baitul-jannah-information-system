<?php

namespace App\Filament\Resources\SemesterClassResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Murid'),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('guardian')
                    ->label('Orang tua/ wali murid')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('entry_date')->label('tanggal masuk'),
                Forms\Components\FileUpload::make('profile_picture_url')
                    ->image()
                    ->directory('student-profiles')
                    ->label('foto profil'),
                Forms\Components\TextInput::make('guardian_number')
                    ->tel()
                    ->maxLength(255)
                    ->label('Nomor telepon orang tua/ wali murid'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
