<?php

namespace App\Filament\Resources\ClassSessionResource\Pages;

use App\Filament\Resources\ClassSessionResource;
use App\Models\Attendance;
use App\Models\ClassSession;
use Filament\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ManageClassSession extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = ClassSessionResource::class;

    protected static string $view = 'filament.resources.class-session-resource.pages.manage-class-session';

    public ClassSession $record;

    public ?array $data = [];

    protected ?string $maxContentWidth = 'full';

    public function mount(ClassSession $record): void
    {
        $this->record = $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Class Sessions')
                ->url(ClassSessionResource::getUrl())
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    public function table(Table $table): Table
    {
        $gradeOptions = [
            '-' => '-',
            'A' => 'A',
            'B+' => 'B+',
            'B' => 'B',
            'B-' => 'B-',
            'C' => 'C',
        ];

        $classSessionId = $this->record->id;

        return $table
            ->query(
                fn () => $this->record
                ->semesterClass
                ->students()
                ->getQuery()
                ->with([
                    'attendances',
                    'studentAchievements.achievement',
                ])
            )
            ->columns([
                Tables\Columns\ImageColumn::make('profile_picture_url')
                    ->label('Photo')
                    ->circular(),

                Tables\Columns\TextColumn::make('student_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('attendance')
                    ->label('Status')
                    ->getStateUsing(function ($record) use ($classSessionId) {
                        $att = $record->attendances
                            ->firstWhere('class_session_id', $classSessionId);

                        return $att
                            ? match ($att->status) {
                                'hadir' => 'Hadir',
                                'sakit' => 'Sakit',
                                'ijin' => 'Ijin',
                                'absen' => 'Absen',
                                default => $att->status,
                            }
                        : 'Not Set';
                    })
                    ->badge()
                    ->color(fn ($record) => match (
                        optional(
                            $record->attendances
                                ->firstWhere('class_session_id', $classSessionId)
                        )->status
                    ) {
                        'hadir' => 'success',
                        'sakit' => 'info',
                        'ijin' => 'warning',
                        'absen' => 'danger',
                        default => 'gray',
                    }),

                // Updated to show multiple achievements
                Tables\Columns\TextColumn::make('achievements')
                    ->label('Achievements')
                    ->getStateUsing(function ($record) use ($classSessionId) {
                        $achievements = $record->studentAchievements
                            ->where('class_session_id', $classSessionId)
                            ->pluck('achievement.achievement_name')
                            ->filter()
                            ->unique();

                        return $achievements->count() > 0
                            ? $achievements->join(', ')
                            : '—';
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('achievement_count')
                    ->label('Count')
                    ->getStateUsing(function ($record) use ($classSessionId) {
                        return $record->studentAchievements
                            ->where('class_session_id', $classSessionId)
                            ->count();
                    })
                    ->badge()
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\Action::make('setAttendance')
                    ->label('Set Attendance')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->form([
                        Select::make('status')
                            ->options([
                                'hadir' => 'Hadir',
                                'sakit' => 'Sakit',
                                'ijin' => 'Ijin',
                                'absen' => 'Absen',
                            ])
                            ->required(),
                        Textarea::make('remarks')
                            ->label('Remarks')
                            ->maxLength(255),
                    ])
                    ->action(function (array $data, $record): void {
                        Attendance::updateOrCreate(
                            [
                                'class_session_id' => $this->record->id,
                                'student_id' => $record->id,
                            ],
                            $data
                        );
                        Notification::make()
                            ->title('Attendance updated successfully')
                            ->success()
                            ->send();
                    })
                    ->fillForm(function ($record) {
                        return Attendance::where([
                            'class_session_id' => $this->record->id,
                            'student_id' => $record->id,
                        ])->first()?->toArray() ?? [];
                    }),

 Tables\Actions\Action::make('addAchievement')
        ->label('Add Achievement')
        ->icon('heroicon-o-plus')
        ->color('success')
        ->form(function () use ($gradeOptions) { // Pass $gradeOptions if defined outside
            $categoryOptions = [
                'Ummi' => 'Ummi',
                'Tahfidz' => 'Tahfidz',
                'Doa & Hadist' => 'Doa Hadist',
            ];

            return [
                Select::make('achievement_category')
                    ->label('Kategori Pencapaian')
                    ->options($categoryOptions)
                    ->live() // Make this field reactive
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function (callable $set) {
                        // Reset module and achievement when category changes
                        $set('achievement_module', null);
                        $set('achievement_id', null);
                    }),

                Select::make('achievement_module')
                    ->label(fn (Get $get): string => match ($get('achievement_category')) {
                        'Ummi' => 'Jilid Ke-',
                        'Tahfidz' => 'Juz Ke-',
                        'Doa & Hadist' => 'Modul Ke-',
                        default => 'Modul/Bagian',
                    })
                    ->options(function (Get $get): \Illuminate\Support\Collection { // MODIFIED: Return Support Collection
                        $category = $get('achievement_category');
                        if (!$category) {
                            return collect(); // Return empty Support Collection
                        }
                        // Fetch distinct modules, plucking 'module' for both value and label
                        return \App\Models\Achievement::query()
                            ->where('category', $category)
                            ->orderBy('module') // Order in SQL before plucking
                            ->distinct()       // Get distinct rows based on the selected column(s)
                            ->pluck('module', 'module'); // Returns Support\Collection of ['module_value' => 'module_label']
                    })
                    // REMOVED: getOptionValueFromRecordUsing and getOptionLabelFromRecordUsing
                    ->live() // Make this field reactive
                    ->searchable()
                    ->required()
                    ->visible(fn (Get $get): bool => filled($get('achievement_category'))) // Show only if category is selected
                    ->afterStateUpdated(function (callable $set) {
                        // Reset achievement when module changes
                        $set('achievement_id', null);
                    })
                    ->placeholder(fn (Get $get): string => match ($get('achievement_category')) {
                        'Ummi' => 'Pilih Jilid',
                        'Tahfidz' => 'Pilih Juz',
                        'Doa & Hadist' => 'Pilih Modul',
                        default => 'Pilih Modul/Bagian',
                    }),

                Select::make('achievement_id')
                    ->label('Pencapaian Spesifik')
                    ->options(function (Get $get): \Illuminate\Support\Collection { // This one correctly returns Support\Collection from pluck
                        $category = $get('achievement_category');
                        $module = $get('achievement_module');

                        if (!$category || !$module) {
                            return collect(); // Return empty if category or module not selected
                        }

                        try {
                            $achievements = \App\Models\Achievement::query()
                                ->where('category', $category)
                                ->where('module', $module)
                                ->orderBy('achievement_name') // Or any other relevant order
                                ->get(); // Eloquent Collection

                            if ($achievements->isEmpty()) {
                                \Log::warning('No achievements found for selected category and module', ['category' => $category, 'module' => $module]);
                                return collect(); // Empty Support Collection
                            }
                            return $achievements->pluck('achievement_name', 'id'); // Support Collection
                        } catch (\Exception $e) {
                            \Log::error('Failed to load achievements for form', [
                                'error' => $e->getMessage(),
                                'category' => $category,
                                'module' => $module,
                                'trace' => $e->getTraceAsString(),
                            ]);
                            return collect(); // Empty Support Collection
                        }
                    })
                    ->required()
                    ->searchable()
                    ->preload() // Preload options after they are filtered
                    ->visible(fn (Get $get): bool => filled($get('achievement_category')) && filled($get('achievement_module'))) // Show only if category and module are selected
                    ->hint(function (Get $get) {
                        if (filled($get('achievement_category')) && filled($get('achievement_module'))) {
                            $count = \App\Models\Achievement::query()
                                ->where('category', $get('achievement_category'))
                                ->where('module', $get('achievement_module'))
                                ->count();
                            if ($count === 0) {
                                return 'No achievements available for this category/module. Please create some first.';
                            }
                        } elseif (!filled($get('achievement_category'))) {
                            return 'Pilih kategori terlebih dahulu.';
                        } elseif (!filled($get('achievement_module'))) {
                            return 'Pilih modul/bagian terlebih dahulu.';
                        }
                        return null;
                    }),

                Textarea::make('keterangan')
                    ->maxLength(255)
                    ->label('Description'),
                Textarea::make('catatan')
                    ->maxLength(65535)
                    ->label('Notes'),

                // Grade options (assuming $gradeOptions is available)
                Select::make('makruj')->options($gradeOptions)->default('-'),
                Select::make('mad')->options($gradeOptions)->default('-'),
                Select::make('tajwid')->options($gradeOptions)->default('-'),
                Select::make('kelancaran')->options($gradeOptions)->default('-'),
                Select::make('fashohah')->options($gradeOptions)->default('-'),
            ];
        })
        ->action(function (array $data, $record): void { // $record here is the student
            try {
                // Ensure the selected achievement exists with the given category and module
                // This check is implicitly handled if achievement_id is successfully fetched
                // but an explicit check can be added if desired.
                if (! \App\Models\Achievement::where('id', $data['achievement_id'])->exists()) {
                    throw new \Exception('Selected achievement does not exist or is invalid for the chosen filters.');
                }

                // Assuming $this->record refers to the ClassSession model instance from the page/resource context
                // And $record refers to the student model instance from the table row action
                $classSession = $this->record; // If this action is on a page related to a single ClassSession

                $studentAchievementData = [
                    'student_id' => $record->id, // $record is the student
                    'class_session_id' => $classSession->id, // $this->record is the ClassSession
                    'achievement_id' => $data['achievement_id'],
                    'tanggal' => $classSession->date->format('Y-m-d'), // Use class session date
                    'keterangan' => $data['keterangan'] ?? null,
                    'catatan' => $data['catatan'] ?? null,
                    'makruj' => $data['makruj'] ?? '-',
                    'mad' => $data['mad'] ?? '-',
                    'tajwid' => $data['tajwid'] ?? '-',
                    'kelancaran' => $data['kelancaran'] ?? '-',
                    'fashohah' => $data['fashohah'] ?? '-',
                ];

                \App\Models\StudentAchievement::create($studentAchievementData);

                Notification::make()
                    ->title('Achievement added successfully')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                \Log::error('Achievement save error', [
                    'error' => $e->getMessage(),
                    'data' => $data, // Be cautious logging all data if it contains sensitive info
                    'trace' => $e->getTraceAsString(),
                ]);

                Notification::make()
                    ->title('Error adding achievement')
                    ->body($e->getMessage()) // Provide a user-friendly message
                    ->danger()
                    ->send();
            }
        }),

                // New action to view/manage existing achievements
                Tables\Actions\Action::make('manageAchievements')
                    ->label('Manage Achievements')
                    ->icon('heroicon-o-list-bullet')
                    ->color('info')
                    ->visible(function ($record) use ($classSessionId) {
                        return $record->studentAchievements
                            ->where('class_session_id', $classSessionId)
                            ->count() > 0;
                    })
                    ->form(function ($record) use ($classSessionId) {
                        $achievements = $record->studentAchievements
                            ->where('class_session_id', $classSessionId);

                        $repeaterSchema = [];

                        foreach ($achievements as $index => $achievement) {
                            $repeaterSchema[] = Group::make([
                                Hidden::make("achievements.{$index}.id")
                                    ->default($achievement->id),
                                TextInput::make("achievements.{$index}.achievement_name")
                                    ->label('Achievement')
                                    ->default($achievement->achievement->achievement_name ?? 'Unknown')
                                    ->disabled(),
                                Textarea::make("achievements.{$index}.keterangan")
                                    ->label('Description')
                                    ->default($achievement->keterangan),
                                Textarea::make("achievements.{$index}.catatan")
                                    ->label('Notes')
                                    ->default($achievement->catatan),
                                Select::make("achievements.{$index}.makruj")
                                    ->options(['-' => '-', 'A' => 'A', 'B+' => 'B+', 'B' => 'B', 'B-' => 'B-', 'C' => 'C'])
                                    ->default($achievement->makruj ?? '-'),
                                Select::make("achievements.{$index}.mad")
                                    ->options(['-' => '-', 'A' => 'A', 'B+' => 'B+', 'B' => 'B', 'B-' => 'B-', 'C' => 'C'])
                                    ->default($achievement->mad ?? '-'),
                                Select::make("achievements.{$index}.tajwid")
                                    ->options(['-' => '-', 'A' => 'A', 'B+' => 'B+', 'B' => 'B', 'B-' => 'B-', 'C' => 'C'])
                                    ->default($achievement->tajwid ?? '-'),
                                Select::make("achievements.{$index}.kelancaran")
                                    ->options(['-' => '-', 'A' => 'A', 'B+' => 'B+', 'B' => 'B', 'B-' => 'B-', 'C' => 'C'])
                                    ->default($achievement->kelancaran ?? '-'),
                                Select::make("achievements.{$index}.fashohah")
                                    ->options(['-' => '-', 'A' => 'A', 'B+' => 'B+', 'B' => 'B', 'B-' => 'B-', 'C' => 'C'])
                                    ->default($achievement->fashohah ?? '-'),
                            ])->columns(2);
                        }

                        return $repeaterSchema;
                    })
                    ->action(function (array $data, $record): void {
                        try {
                            if (isset($data['achievements'])) {
                                foreach ($data['achievements'] as $achievementData) {
                                    if (isset($achievementData['id'])) {
                                        \App\Models\StudentAchievement::where('id', $achievementData['id'])
                                            ->update([
                                                'keterangan' => $achievementData['keterangan'] ?? null,
                                                'catatan' => $achievementData['catatan'] ?? null,
                                                'makruj' => $achievementData['makruj'] ?? '-',
                                                'mad' => $achievementData['mad'] ?? '-',
                                                'tajwid' => $achievementData['tajwid'] ?? '-',
                                                'kelancaran' => $achievementData['kelancaran'] ?? '-',
                                                'fashohah' => $achievementData['fashohah'] ?? '-',
                                            ]);
                                    }
                                }
                            }

                            Notification::make()
                                ->title('Achievements updated successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Log::error('Achievement update error', [
                                'error' => $e->getMessage(),
                                'data' => $data,
                                'trace' => $e->getTraceAsString(),
                            ]);

                            Notification::make()
                                ->title('Error updating achievements')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }

    protected function getStatusLabel(string $status): string
    {
        return match ($status) {
            'hadir' => 'Hadir',
            'sakit' => 'Sakit',
            'ijin' => 'Ijin',
            'absen' => 'Absen',
            default => $status,
        };
    }

    protected function getViewData(): array
    {
        return [
            'studentsTable' => $this->table($this->makeTable()),
        ];
    }
}
