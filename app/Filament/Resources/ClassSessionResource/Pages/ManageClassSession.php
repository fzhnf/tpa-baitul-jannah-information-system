<?php

namespace App\Filament\Resources\ClassSessionResource\Pages;

use App\Filament\Resources\ClassSessionResource;
use App\Models\Attendance;
use App\Models\ClassSession;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

        // grab once, for use inside closures:
        $classSessionId = $this->record->id;

        return $table
            // 1) eager-load both relations
            ->query(fn () => $this->record
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

                Tables\Columns\TextColumn::make('achievement')
                    ->label('Achievement')
                    ->getStateUsing(fn ($record) => optional(
                        $record->studentAchievements
                            ->firstWhere('class_session_id', $classSessionId)
                            ?->achievement
                    )->achievement_name
                    // fallback if none
                    ?? '—'),
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

                Tables\Actions\Action::make('setAchievement')
                    ->label('Set Achievement')
                    ->icon('heroicon-o-star')
                    ->form([
                        Select::make('achievement_id')
                            ->options(function () {
                                // Load achievements directly with error handling
                                try {
                                    $achievements = \App\Models\Achievement::orderBy('achievement_name')->get();

                                    if ($achievements->isEmpty()) {
                                        \Log::warning('No achievements found in database');

                                        return [];
                                    }

                                    return $achievements->pluck('achievement_name', 'id')->toArray();
                                } catch (\Exception $e) {
                                    \Log::error('Failed to load achievements', [
                                        'error' => $e->getMessage(),
                                        'trace' => $e->getTraceAsString(),
                                    ]);

                                    return [];
                                }
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->hint(function () {
                                if (\App\Models\Achievement::count() === 0) {
                                    return 'No achievements available. Please create some first.';
                                }

                                return null;
                            }),
                        Textarea::make('keterangan')
                            ->maxLength(255)
                            ->label('Description'),
                        Textarea::make('catatan')
                            ->maxLength(65535)
                            ->label('Notes'),
                        Select::make('makruj')
                            ->options($gradeOptions)
                            ->default('-'),
                        Select::make('mad')
                            ->options($gradeOptions)
                            ->default('-'),
                        Select::make('tajwid')
                            ->options($gradeOptions)
                            ->default('-'),
                        Select::make('kelancaran')
                            ->options($gradeOptions)
                            ->default('-'),
                        Select::make('fashohah')
                            ->options($gradeOptions)
                            ->default('-'),
                    ])
                    ->action(function (array $data, $record): void {
                        try {
                            // Validate achievement exists
                            if (! \App\Models\Achievement::where('id', $data['achievement_id'])->exists()) {
                                throw new \Exception('Selected achievement does not exist');
                            }

                            $data['tanggal'] = $this->record->date->format('Y-m-d');
                            $data['class_session_id'] = $this->record->id;
                            $data['student_id'] = $record->id;

                            \App\Models\StudentAchievement::updateOrCreate(
                                [
                                    'class_session_id' => $this->record->id,
                                    'student_id' => $record->id,
                                ],
                                $data
                            );

                            Notification::make()
                                ->title('Achievement updated successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Log::error('Achievement save error', [
                                'error' => $e->getMessage(),
                                'data' => $data,
                                'trace' => $e->getTraceAsString(),
                            ]);

                            Notification::make()
                                ->title('Error saving achievement')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->fillForm(function ($record) {
                        try {
                            $achievement = \App\Models\StudentAchievement::with(['achievement'])
                                ->where('class_session_id', $this->record->id)
                                ->where('student_id', $record->id)
                                ->first();

                            if (! $achievement) {
                                return [];
                            }

                            // Verify the related achievement exists
                            if (! $achievement->achievement) {
                                \Log::warning('Orphaned student achievement found', [
                                    'student_achievement_id' => $achievement->id,
                                    'missing_achievement_id' => $achievement->achievement_id,
                                ]);

                                return $achievement->only([
                                    'keterangan', 'catatan', 'makruj', 'mad',
                                    'tajwid', 'kelancaran', 'fashohah',
                                ]);
                            }

                            return $achievement->toArray();
                        } catch (\Exception $e) {
                            \Log::error('Achievement fillForm error', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                            ]);

                            return [];
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
