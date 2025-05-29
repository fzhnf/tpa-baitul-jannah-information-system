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

    protected static ?int $navigationSort = 5; // Smaller number = higher up


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
            'Ummi' => 'Ummi',
            'Tahfidz' => 'Tahfidz',
            'Doa & Hadist' => 'Doa & Hadist',
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

        // Category Select
        Forms\Components\Select::make('achievement_category_filter')
            ->label('Kategori Pencapaian')
            ->options([
                'Ummi' => 'Ummi',
                'Tahfidz' => 'Tahfidz',
                'Doa & Hadist' => 'Doa & Hadist',
            ])
            ->live()
            ->required()
            ->afterStateUpdated(function (callable $set) {
                $set('achievement_module_filter', null);
                $set('achievement_id', null);
            }),

        // Module Select
        Forms\Components\Select::make('achievement_module_filter')
            ->label(fn (Get $get): string => match ($get('achievement_category_filter')) {
                'Ummi' => 'Jilid Ke-',
                'Tahfidz' => 'Juz Ke-',
                'Doa & Hadist' => 'Modul Ke-',
                default => 'Modul/Bagian',
            })
            ->options(function (Get $get): array {
                $category = $get('achievement_category_filter');
                return $category
                    ? Achievement::where('category', $category)
                        ->distinct('module')
                        ->pluck('module', 'module')
                        ->toArray()
                    : [];
            })
            ->live()
            ->required()
            ->visible(fn (Get $get): bool => filled($get('achievement_category_filter')))
            ->afterStateUpdated(fn (callable $set) => $set('achievement_id', null)),

        // Achievement Select
        Forms\Components\Select::make('achievement_id')
            ->label('Pencapaian Spesifik')
            ->options(function (Get $get): array {
                $category = $get('achievement_category_filter');
                $module = $get('achievement_module_filter');

                if (!$category || !$module) {
                    return [];
                }

                return Achievement::where('category', $category)
                    ->where('module', $module)
                    ->pluck('achievement_name', 'id')
                    ->toArray();
            })
            ->required()
            ->searchable()
            ->visible(
                fn (Get $get): bool =>
                filled($get('achievement_category_filter')) &&
                filled($get('achievement_module_filter'))
            )
            ->placeholder(function (Get $get): string {
                if (!$get('achievement_category_filter')) {
                    return 'Pilih kategori terlebih dahulu';
                }
                if (!$get('achievement_module_filter')) {
                    return 'Pilih modul terlebih dahulu';
                }
                return 'Pilih pencapaian';
            }),


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
        ->default('-')
        ->visible(fn (Get $get): bool =>
            in_array($get('achievement_category_filter'), ['Ummi', 'Tahfidz'])),

    Forms\Components\Select::make('mad')
        ->options($gradeOptions)
        ->default('-')
        ->visible(fn (Get $get): bool =>
            in_array($get('achievement_category_filter'), ['Ummi', 'Tahfidz'])),

    Forms\Components\Select::make('tajwid')
        ->options($gradeOptions)
        ->default('-')
        ->visible(fn (Get $get): bool =>
            in_array($get('achievement_category_filter'), ['Ummi', 'Tahfidz'])),

    Forms\Components\Select::make('kelancaran')
        ->options($gradeOptions)
        ->default('-'),

    Forms\Components\Select::make('fashohah')
        ->options($gradeOptions)
        ->default('-')
        ->visible(fn (Get $get): bool =>
            $get('achievement_category_filter') === 'Doa & Hadist'),
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
                    ->date('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('achievement.achievement_name')
                    ->label('Pencapaian')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('achievement.category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(function (string $state): string {
                        $categoryDisplay = [
                            'Ummi' => 'Ummi',
                            'Tahfidz' => 'Tahfidz',
                            'Doa & Hadist' => 'Doa Hadist',
                        ];
                        return $categoryDisplay[$state] ?? ucfirst($state);
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Ummi' => 'primary',
                        'Tahfidz' => 'success',
                        'Doa & Hadist' => 'warning',
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
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->keterangan),

                // Grade columns with sorting
                Tables\Columns\TextColumn::make('makruj')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mad')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tajwid')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelancaran')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fashohah')
                    ->sortable()
                    ->searchable(),
            ])
        ->filters([
            // Ummi Filter
            Tables\Filters\Filter::make('Ummi')
                ->label('Ummi')
                ->query(fn (Builder $query): Builder => $query->whereHas(
                    'achievement',
                    fn ($q) => $q->where('category', 'Ummi')
                ))
                ->indicator('Ummi')
                ->toggle(),

            // Tahfidz Filter
            Tables\Filters\Filter::make('Tahfidz')
                ->label('Tahfidz')
                ->query(fn (Builder $query): Builder => $query->whereHas(
                    'achievement',
                    fn ($q) => $q->where('category', 'Tahfidz')
                ))
                ->indicator('Tahfidz')
                ->toggle(),

            // Doa & Hadist Filter
            Tables\Filters\Filter::make('Doa & Hadist')
                ->label('Doa & Hadist')
                ->query(fn (Builder $query): Builder => $query->whereHas(
                    'achievement',
                    fn ($q) => $q->where('category', 'Doa & Hadist')
                ))
                ->indicator('Doa & Hadist')
                ->toggle()
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
