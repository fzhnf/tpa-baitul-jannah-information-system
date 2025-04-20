<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemesterClassResource\Pages;
use App\Filament\Resources\SemesterClassResource\RelationManagers;
use App\Models\SemesterClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SemesterClassResource extends Resource
{
    protected static ?string $model = SemesterClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'id')
                    ->required(),
                Forms\Components\TextInput::make('nama_semester_class')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semester.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_semester_class')
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
            'index' => Pages\ListSemesterClasses::route('/'),
            'create' => Pages\CreateSemesterClass::route('/create'),
            'edit' => Pages\EditSemesterClass::route('/{record}/edit'),
        ];
    }
}
