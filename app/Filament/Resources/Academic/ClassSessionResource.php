<?php

namespace App\Filament\Resources\Academic;

use App\Filament\Resources\Academic\ClassSessionResource\Pages;
use App\Filament\Resources\Academic\ClassSessionResource\RelationManagers;
use App\Models\Academic\ClassSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClassSessionResource extends Resource
{
    protected static ?string $model = ClassSession::class;

    protected static ?string $modelLabel = "Sesi Kelas";
    protected static ?string $pluralLabel = "Sesi Kelas";

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Manajemen Akademik';

    protected static ?int $navigationSort = 1; // Smaller number = higher up

    public static function form(Form $form): Form
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
                    ->label('Tanggal & Waktu'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semesterClass.nama_semester_class')
                    ->sortable()
                    ->searchable()
                    ->label('Kelas')
                    ->tooltip(fn ($record) => $record->semesterClass->nama_semester_class)
                    ->extraAttributes([
                        'class' => 'max-w-[200px] whitespace-nowrap overflow-hidden relative pr-4',
                        'style' => 'mask-image: linear-gradient(to right, black 80%, transparent); -webkit-mask-image: linear-gradient(to right, black 80%, transparent);',
                    ]),
                Tables\Columns\TextColumn::make('semesterClass.semester.school_year')
                    ->sortable()
                    ->searchable()
                    ->label('Tahun Ajaran'),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal & Waktu'),
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
                Tables\Actions\EditAction::make()->label('Perbaharui'),
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\Action::make('manage')
                    ->label('Mengelola')
                    ->icon('heroicon-o-cog')
                    ->url(fn (ClassSession $record): string => static::getUrl('manage', ['record' => $record]))
                    ->color('success'),

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
            RelationManagers\AttendancesRelationManager::class,
            RelationManagers\StudentAchievementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassSessions::route('/'),
            'create' => Pages\CreateClassSession::route('/create'),
            'view' => Pages\ViewClassSession::route('/{record}'),
            'edit' => Pages\EditClassSession::route('/{record}/edit'),
            'manage' => Pages\ManageClassSession::route('/{record}/manage'),
        ];
    }
}
