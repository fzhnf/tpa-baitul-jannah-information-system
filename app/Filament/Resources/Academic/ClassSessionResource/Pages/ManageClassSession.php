<?php

namespace App\Filament\Resources\Academic\ClassSessionResource\Pages;

use App\Filament\Resources\Academic\ClassSessionResource;
use App\Models\Academic\Attendance;
use App\Models\Academic\ClassSession;
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

class ManageClassSession extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = ClassSessionResource::class;

    protected static string $view = 'filament.resources.academic.class-session-resource.pages.manage-class-session';

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
                    Tables\Columns\TextColumn::make('achievements')
                        ->label('Achievements')
                        ->getStateUsing(function ($record) use ($classSessionId) {
                            $achievements = $record->studentAchievements
                                ->where('class_session_id', $classSessionId)
                                ->pluck('achievement.achievement_name')
                                ->filter()
                                ->unique()
                                ->toArray(); // Convert to array

                            return $achievements; // Return array of achievement names
                        })
                        ->listWithLineBreaks() // Display each achievement on a new line
                        ->wrap(),
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
                    ->form(function () use ($gradeOptions) {
                        $categoryOptions = [
                            'Ummi' => 'Ummi',
                            'Tahfidz' => 'Tahfidz',
                            'Doa & Hadist' => 'Doa Hadist',
                        ];

                        return [
                            Select::make('achievement_category')
                                ->label('Kategori Pencapaian')
                                ->options($categoryOptions)
                                ->live()
                                ->searchable()
                                ->required()
                                ->afterStateUpdated(function (callable $set) {
                                    // Reset all dependent fields when category changes
                                    $set('achievement_module', null);
                                    $set('achievement_id', null);
                                    $set('mad', null);
                                    $set('makruj', null);
                                    $set('tajwid', null);
                                    $set('kelancaran', null);
                                    $set('fashohah', null);
                                }),

                            Select::make('achievement_module')
                                ->label(fn (Get $get): string => match ($get('achievement_category')) {
                                    'Ummi' => 'Jilid Ke-',
                                    'Tahfidz' => 'Juz Ke-',
                                    'Doa & Hadist' => 'Modul Ke-',
                                    default => 'Modul/Bagian',
                                })
                                ->options(function (Get $get): \Illuminate\Support\Collection {
                                    $category = $get('achievement_category');
                                    if (!$category) {
                                        return collect();
                                    }
                                    return \App\Models\Academic\Achievement::query()
                                        ->orderBy('module')
                                        ->distinct()
                                        ->pluck('module', 'module');
                                })
                                ->live()
                                ->searchable()
                                ->required()
                                ->visible(fn (Get $get): bool => filled($get('achievement_category')))
                                ->afterStateUpdated(function (callable $set) {
                                    // Reset dependent fields when module changes
                                    $set('achievement_id', null);
                                    $set('mad', null);
                                    $set('makruj', null);
                                    $set('tajwid', null);
                                    $set('kelancaran', null);
                                    $set('fashohah', null);
                                })
                                ->placeholder(fn (Get $get): string => match ($get('achievement_category')) {
                                    'Ummi' => 'Pilih Jilid',
                                    'Tahfidz' => 'Pilih Juz',
                                    'Doa & Hadist' => 'Pilih Modul',
                                    default => 'Pilih Modul/Bagian',
                                }),

                            Select::make('achievement_id')
                                ->label('Pencapaian Spesifik')
                                ->options(function (Get $get): \Illuminate\Support\Collection {
                                    $category = $get('achievement_category');
                                    $module = $get('achievement_module');

                                    if (!$category || !$module) {
                                        return collect();
                                    }

                                    try {
                                        $achievements = \App\Models\Academic\Achievement::query()
                                            ->where('module', $module)
                                            ->orderBy('achievement_name')
                                            ->get();

                                        if ($achievements->isEmpty()) {
                                            \Log::warning('No achievements found for selected category and module', ['category' => $category, 'module' => $module]);
                                            return collect();
                                        }
                                        return $achievements->pluck('achievement_name', 'id');
                                    } catch (\Exception $e) {
                                        \Log::error('Failed to load achievements for form', [
                                            'error' => $e->getMessage(),
                                            'category' => $category,
                                            'module' => $module,
                                            'trace' => $e->getTraceAsString(),
                                        ]);
                                        return collect();
                                    }
                                })
                                ->required()
                                ->searchable()
                                ->preload()
                                ->visible(fn (Get $get): bool => filled($get('achievement_category')) && filled($get('achievement_module')))
                                ->afterStateUpdated(function (callable $set) {
                                    // Reset grade fields when achievement changes
                                    $set('mad', null);
                                    $set('makruj', null);
                                    $set('tajwid', null);
                                    $set('kelancaran', null);
                                    $set('fashohah', null);
                                })
                                ->hint(function (Get $get) {
                                    if (filled($get('achievement_category')) && filled($get('achievement_module'))) {
                                        $count = \App\Models\Academic\Achievement::query()
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


                            // Conditional grade fields based on category
                            Select::make('mad')
                                ->label('Mad')
                                ->options($gradeOptions)
                                ->default('-')
                                ->visible(fn (Get $get): bool => in_array($get('achievement_category'), ['Ummi', 'Tahfidz'])),

                            Select::make('makruj')
                                ->label('Makruj')
                                ->options($gradeOptions)
                                ->default('-')
                                ->visible(fn (Get $get): bool => in_array($get('achievement_category'), ['Ummi', 'Tahfidz'])),

                            Select::make('tajwid')
                                ->label('Tajwid')
                                ->options($gradeOptions)
                                ->default('-')
                                ->visible(fn (Get $get): bool => in_array($get('achievement_category'), ['Ummi', 'Tahfidz'])),

                            Select::make('kelancaran')
                                ->label('Kelancaran')
                                ->options($gradeOptions)
                                ->default('-')
                                ->visible(fn (Get $get): bool => filled($get('achievement_category'))), // Show for all categories

                            Select::make('fashohah')
                                ->label('Fashohah')
                                ->options($gradeOptions)
                                ->default('-')
                                ->visible(fn (Get $get): bool => $get('achievement_category') === 'Doa & Hadist'),
                        ];
                    })
                    ->action(function (array $data, $record): void {
                        try {
                            if (! \App\Models\Academic\Achievement::where('id', $data['achievement_id'])->exists()) {
                                throw new \Exception('Selected achievement does not exist.');
                            }

                            $classSession = $this->record;

                            $studentAchievementData = [
                                'student_id' => $record->id,
                                'class_session_id' => $classSession->id,
                                'achievement_id' => $data['achievement_id'],
                                'tanggal' => $classSession->date->format('Y-m-d'),
                                'keterangan' => $data['keterangan'] ?? null,
                                'makruj' => $data['makruj'] ?? '-',
                                'mad' => $data['mad'] ?? '-',
                                'tajwid' => $data['tajwid'] ?? '-',
                                'kelancaran' => $data['kelancaran'] ?? '-',
                                'fashohah' => $data['fashohah'] ?? '-',
                            ];

                            \App\Models\Academic\StudentAchievement::create($studentAchievementData);

                            Notification::make()
                                ->title('Achievement added successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Log::error('Achievement save error', [
                                'error' => $e->getMessage(),
                                'data' => $data,
                                'trace' => $e->getTraceAsString(),
                            ]);

                            Notification::make()
                                ->title('Error adding achievement')
                                ->body($e->getMessage())
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
                    ->form(function ($record) use ($classSessionId, $gradeOptions) {
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
                                Select::make("achievements.{$index}.makruj")
                                    ->label('Makruj')
                                    ->options($gradeOptions)
                                    ->default($achievement->makruj ?? '-'),
                                Select::make("achievements.{$index}.mad")
                                    ->label('Mad')
                                    ->options($gradeOptions)
                                    ->default($achievement->mad ?? '-'),
                                Select::make("achievements.{$index}.tajwid")
                                    ->label('Tajwid')
                                    ->options($gradeOptions)
                                    ->default($achievement->tajwid ?? '-'),
                                Select::make("achievements.{$index}.kelancaran")
                                    ->label('Kelancaran')
                                    ->options($gradeOptions)
                                    ->default($achievement->kelancaran ?? '-'),
                                Select::make("achievements.{$index}.fashohah")
                                    ->label('Fashohah')
                                    ->options($gradeOptions)
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
                                        \App\Models\Academic\StudentAchievement::where('id', $achievementData['id'])
                                            ->update([
                                                'keterangan' => $achievementData['keterangan'] ?? null,
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
