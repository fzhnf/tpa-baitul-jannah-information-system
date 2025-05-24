<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchievementResource\Pages;
use App\Filament\Resources\AchievementResource\RelationManagers;
use App\Models\Achievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get; // Import Get for reactive fields
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter; // Import base Filter for text input filter
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static ?string $modelLabel = "Pencapaian";
    protected static ?string $pluralLabel = "Pencapaian";

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Manajemen Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('achievement_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Pencapaian'),
                Forms\Components\Select::make('category')
                    ->options([
                        'ummi' => 'Ummi',
                        'tahfidz' => 'Tahfidz',
                        'doaHadist' => 'Doa Hadist',
                    ])
                    ->required()
                    ->live() // Make category reactive for module field
                    ->label('Kategori'),
                Forms\Components\TextInput::make('module')
                    ->required()
                    ->maxLength(255)
                    // Dynamic label based on category
                    ->label(fn (Get $get): string => match ($get('category')) {
                        'ummi' => 'Jilid Ke-',
                        'tahfidz' => 'Juz Ke-',
                        'doaHadist' => 'Modul Ke-',
                        default => 'Modul/Bagian',
                    })
                    // Dynamic placeholder
                    ->placeholder(fn (Get $get): string => match ($get('category')) {
                        'ummi' => 'Contoh: 1, 2, ..., 6, Ghorib, Tajwid',
                        'tahfidz' => 'Contoh: 1, 2, ..., 30, Amma',
                        'doaHadist' => 'Contoh: 1, Pilihan, Harian',
                        default => 'Masukkan detail modul/bagian',
                    })
                    // Show this field only after a category is selected
                    ->visible(fn (Get $get): bool => filled($get('category'))),
            ]);
    }

    public static function table(Table $table): Table
    {
        $categoryOptions = [
            'ummi' => 'Ummi',
            'tahfidz' => 'Tahfidz',
            'doaHadist' => 'Doa Hadist',
        ];

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('achievement_name')
                    ->searchable()
                    ->sortable()
                    ->label('Pencapaian'),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $categoryOptions[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'ummi' => 'primary',
                        'tahfidz' => 'success',
                        'doaHadist' => 'warning',
                        default => 'gray',
                    })
                    ->sortable()
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('module') // New column for module
                    ->searchable()
                    ->sortable()
                    ->label('Modul/Bagian'), // Generic label for the table
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
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options($categoryOptions)
                    ->multiple(),
                // Filter for the module field (simple text input filter)
                Filter::make('module')
                    ->form([
                        Forms\Components\TextInput::make('module_value')
                            ->label('Filter Modul/Bagian')
                            ->placeholder('Cari berdasarkan modul...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['module_value'],
                            fn (Builder $query, $value): Builder => $query->where('module', 'like', "%{$value}%")
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['module_value']) {
                            return null;
                        }
                        return 'Modul/Bagian: ' . $data['module_value'];
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Perbaharui'),
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
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
            RelationManagers\StudentAchievementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'view' => Pages\ViewAchievement::route('/{record}'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }
}
