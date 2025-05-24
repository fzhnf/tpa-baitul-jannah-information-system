<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentAchievementResource\Pages;
use App\Models\Achievement; // Import the Achievement model
use App\Models\StudentAchievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get; // Import Get
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection; // Import Collection

class StudentAchievementResource extends Resource
{
    protected static ?string $model = StudentAchievement::class;

    protected static ?string $modelLabel = "Pencapaian Murid";
    protected static ?string $pluralLabel = "Pencapaian Murid";

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Manajemen Akademik';

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

        // Define your category options here, similar to AchievementResource
        $achievementCategories = [
            'ummi' => 'Ummi',
            'tahfidz' => 'Tahfidz',
            'doaHadist' => 'Doa Hadist',
            // Add other categories if you have more
        ];

        return $form->schema([
            Forms\Components\Select::make('student_id')
                ->relationship('student', 'student_name')
                ->required()
                ->searchable()
                ->preload() // Consider adding preload for better UX if student list is not too large
                ->label('Nama Murid'),

            Forms\Components\Select::make('class_session_id')
                ->relationship('classSession', 'date', function (Builder $query) {
                    return $query->orderBy('date', 'desc');
                })
                ->getOptionLabelFromRecordUsing(
                    fn ($record) => $record->semesterClass->nama_semester_class.' - '.$record->date->format('d M Y H:i')
                )
                ->label('Sesi Kelas')
                ->placeholder('e.g: 2025-05-03 15:00')
                ->required()
                ->searchable()
                ->preload(), // Consider adding preload

            Forms\Components\DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal'),

            // New Category Select Field using defined options
            Forms\Components\Select::make('achievement_category_filter') // Changed name to avoid conflict if you have 'category' in StudentAchievement
                ->label('Kategori Pencapaian')
                ->options($achievementCategories) // Use the predefined array
                ->live() // Make this field reactive
                ->searchable()
                ->required(),

            Forms\Components\Select::make('achievement_id')
                ->label('Pencapaian')
                ->options(function (Get $get): Collection {
                    $category = $get('achievement_category_filter'); // Use the correct field name
                    if (!$category) {
                        return collect();
                    }
                    return Achievement::query()
                        ->where('category', $category) // This assumes 'category' column in 'achievements' table stores 'ummi', 'tahfidz', etc.
                        ->pluck('achievement_name', 'id');
                })
                ->required()
                ->searchable()
                // ->live() // Only if other fields depend on this
                ->disabled(fn (Get $get): bool => !$get('achievement_category_filter'))
                ->placeholder(fn (Get $get): string => $get('achievement_category_filter') ? 'Pilih pencapaian' : 'Pilih kategori terlebih dahulu'),


            Forms\Components\TextInput::make('keterangan')
                ->maxLength(255)
                ->label('Keterangan'),

            Forms\Components\Textarea::make('catatan')
                ->columnSpanFull()
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.student_name')
                    ->label('Murid')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('classSession.date')
                    ->label('Sesi Kelas')
                    ->date('d M Y H:i') // Format the date
                    ->sortable(),

                Tables\Columns\TextColumn::make('achievement.achievement_name')
                    ->label('Pencapaian')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('achievement.category')
                    ->label('Kategori')
                    ->badge()
                     ->formatStateUsing(function (string $state): string {
                        // Assuming your AchievementResource uses these keys for display
                        $categoryDisplay = [
                            'ummi' => 'Ummi',
                            'tahfidz' => 'Tahfidz',
                            'doaHadist' => 'Doa Hadist',
                        ];
                        return $categoryDisplay[$state] ?? ucfirst($state);
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'ummi' => 'primary',
                        'tahfidz' => 'success',
                        'doaHadist' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(30) // Optionally limit length in table
                    ->tooltip(fn ($record) => $record->keterangan), // Show full text on hover

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
                Tables\Actions\EditAction::make()
                    ->label('Perbaharui'),
                Tables\Actions\ViewAction::make(), // Added ViewAction for consistency
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([ // Grouping bulk actions
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'view' => Pages\ViewStudentAchievement::route('/{record}'),
            'edit' => Pages\EditStudentAchievement::route('/{record}/edit'),
        ];
    }
}
