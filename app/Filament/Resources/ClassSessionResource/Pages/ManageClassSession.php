<?php

namespace App\Filament\Resources\ClassSessionResource\Pages;

use App\Filament\Resources\ClassSessionResource;
use App\Models\Attendance;
use App\Models\ClassSession;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                ->icon('heroicon-o-arrow-left')
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return $this->record->semesterClass->students()->getQuery()
                    ->with(['attendances' => function ($query) {
                        $query->where('class_session_id', $this->record->id);
                    }]);
            })
            ->columns([
                Tables\Columns\ImageColumn::make('profile_picture_url')
                    ->label('Photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('student_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendances')
                    ->label('Status')
                    ->formatStateUsing(function ($state, $record) {
                        $attendance = $record->attendances->firstWhere('class_session_id', $this->record->id);
                        return $attendance ? $this->getStatusLabel($attendance->status) : 'Not Set';
                    })
                    ->badge()
                    ->color(function ($state, $record) {
                        $attendance = $record->attendances->firstWhere('class_session_id', $this->record->id);
                        if (!$attendance) return 'gray';

                        return match ($attendance->status) {
                            'hadir' => 'success',
                            'sakit' => 'info',
                            'ijin' => 'warning',
                            'absen' => 'danger',
                            default => 'gray',
                        };
                    }),
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
                        $attendance = Attendance::firstOrNew([
                            'class_session_id' => $this->record->id,
                            'student_id' => $record->id,
                        ]);

                        $attendance->fill($data);
                        $attendance->save();

                        Notification::make()
                            ->title('Kehadiran berhasil diperbarui')
                            ->success()
                            ->send();
                    })
                    ->fillForm(function ($record) {
                        $attendance = Attendance::where([
                            'class_session_id' => $this->record->id,
                            'student_id' => $record->id,
                        ])->first();

                        return $attendance ? [
                            'status' => $attendance->status,
                            'remarks' => $attendance->remarks,
                        ] : [];
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
