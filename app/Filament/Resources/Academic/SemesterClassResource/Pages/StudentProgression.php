<?php

namespace App\Filament\Resources\Academic\SemesterClassResource\Pages;

use App\Filament\Resources\Academic\SemesterClassResource;
use App\Models\Academic\Student;
use App\Models\Academic\SemesterClass;
use App\Models\Academic\StudentAchievement;
use App\Models\Academic\ClassNote;
use Filament\Resources\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class StudentProgression extends Page implements HasTable, HasForms, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string $resource = SemesterClassResource::class;
    protected static string $view = 'filament.resources.academic.semester-class-resource.pages.student-progression';

    public SemesterClass $semesterClass;
    public Student $student;
    public ?array $classNoteData = [];
    public string $navigationSource = 'class'; // 'class' or 'student'

    // Original mount method for class -> student navigation
    public function mount(SemesterClass $semesterClass, Student $student): void
    {
        $this->semesterClass = $semesterClass;
        $this->student = $student;
        $this->navigationSource = 'class';
        $this->loadClassNoteData();
    }

    // Alternative mount method for student -> class navigation
    public function mountFromStudent(Student $student, SemesterClass $semesterClass): void
    {
        $this->student = $student;
        $this->semesterClass = $semesterClass;
        $this->navigationSource = 'student';
        $this->loadClassNoteData();
    }

    private function loadClassNoteData(): void
    {
        // Load existing class note data
        $classNote = ClassNote::where([
            'semester_class_id' => $this->semesterClass->id,
            'student_id' => $this->student->id,
        ])->first();

        $this->classNoteData = $classNote ? $classNote->grade_aspects : [
            ['aspect' => 'JILID & AL-QUR\'AN', 'note' => ''],
            ['aspect' => 'HAFALAN', 'note' => ''],
            ['aspect' => 'ADAB', 'note' => ''],
            ['aspect' => 'KEHADIRAN', 'note' => ''],
        ];

        $this->form->fill(['grade_aspects' => $this->classNoteData]);
    }

    public function getTitle(): string
    {
        return "Progres {$this->student->student_name} - {$this->semesterClass->nama_semester_class}";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('achievement.achievement_name')
                    ->label('Achievement')
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('achievement.category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ummi' => 'primary',
                        'Tahfidz' => 'success',
                        'Doa & Hadist' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('achievement.module')
                    ->label('Module')
                    ->badge()
                    ->color('gray'),

                // Conditional evaluation columns
                TextColumn::make('makruj')
                    ->label('M')
                    ->getStateUsing(fn ($record) => $this->getEvaluationValue($record, 'makruj'))
                    ->badge()
                    ->color(fn ($record) => $this->getEvaluationColor($record, 'makruj')),

                TextColumn::make('mad')
                    ->label('MAD')
                    ->getStateUsing(fn ($record) => $this->getEvaluationValue($record, 'mad'))
                    ->badge()
                    ->color(fn ($record) => $this->getEvaluationColor($record, 'mad')),

                TextColumn::make('tajwid')
                    ->label('T')
                    ->getStateUsing(fn ($record) => $this->getEvaluationValue($record, 'tajwid'))
                    ->badge()
                    ->color(fn ($record) => $this->getEvaluationColor($record, 'tajwid')),

                TextColumn::make('kelancaran')
                    ->label('K')
                    ->badge()
                    ->color('success'),

                TextColumn::make('fashohah')
                    ->label('F')
                    ->getStateUsing(fn ($record) => $this->getEvaluationValue($record, 'fashohah'))
                    ->badge()
                    ->color(fn ($record) => $this->getEvaluationColor($record, 'fashohah')),
            ])
            ->striped();
    }

    protected function getTableQuery(): Builder
    {
        return StudentAchievement::query()
            ->with(['achievement', 'classSession.semesterClass'])
            ->where('student_id', $this->student->id)
            ->whereHas('classSession', function (Builder $query) {
                $query->where('semester_class_id', $this->semesterClass->id);
            })
            ->orderBy('tanggal', 'desc');
    }

    /**
     * @param mixed $record
     */
    private function getEvaluationValue($record, string $field): string
    {
        $category = $record->achievement->category;

        return match ($field) {
            'makruj', 'mad', 'tajwid' => in_array($category, ['Ummi', 'Tahfidz']) ? $record->{$field} : '-',
            'fashohah' => $category === 'Doa & Hadist' ? $record->{$field} : '-',
            default => $record->{$field} ?? '-'
        };
    }

    /**
     * @param mixed $record
     */
    private function getEvaluationColor($record, string $field): string
    {
        $category = $record->achievement->category;

        return match ($field) {
            'makruj', 'mad', 'tajwid' => in_array($category, ['Ummi', 'Tahfidz']) ? 'primary' : 'gray',
            'fashohah' => $category === 'Doa & Hadist' ? 'warning' : 'gray',
            default => 'gray'
        };
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Catatan Kelas - Deskripsi Aspek')
                    ->description('Tambahkan catatan untuk setiap aspek penilaian siswa')
                    ->schema([
                        Repeater::make('grade_aspects')
                            ->label('')
                            ->schema([
                                TextInput::make('aspect')
                                    ->label('Aspek')
                                    ->required()
                                    ->placeholder('e.g., JILID & AL-QUR\'AN')
                                    ->columnSpan(1),

                                Textarea::make('note')
                                    ->label('Catatan')
                                    ->required()
                                    ->rows(2)
                                    ->placeholder('Masukkan catatan detail untuk aspek ini')
                                    ->columnSpan(2),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['aspect'] ?? null)
                            ->addActionLabel('+ Aspek')
                            ->reorderableWithButtons()
                            ->minItems(1)
                            ->defaultItems(0)
                            ->grid(1),
                    ])
                    ->collapsible()
                    ->persistCollapsed()
                    ->compact()
                    ->headerActions([
                        \Filament\Forms\Components\Actions\Action::make('saveClassNote')
                            ->label('Simpan')
                            ->icon('heroicon-o-check')
                            ->color('success')
                            ->size('sm')
                            ->action('saveClassNote'),
                    ]),
            ])
            ->statePath('classNoteData');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('printPDF')
                ->label('Cetak Rapor PDF')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->form([
                    Section::make('Informasi Institusi')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('institution_name')
                                        ->label('Nama TPA')
                                        ->default('Baitul Jannah')
                                        ->required(),

                                    TextInput::make('location')
                                        ->label('Lokasi')
                                        ->default('Balikpapan')
                                        ->required(),

                                    Textarea::make('institution_address')
                                        ->label('Alamat TPA')
                                        ->default('Perum Griya Diva Residence Jl. Karya Bakti km 9,5 Rt 08, Kel Karang Joang Kec. Bpp Utara')
                                        ->required()
                                        ->columnSpanFull(),

                                    TextInput::make('teacher_name')
                                        ->label('Nama Ustadz/ah')
                                        ->default('Syahrum Mubarokah, S.Pd.')
                                        ->required(),

                                    TextInput::make('principal_name')
                                        ->label('Nama Kepala TPA')
                                        ->default('Darwaman Syah S.Pd')
                                        ->required(),
                                ]),

                            FileUpload::make('logo_file')
                                ->label('Logo TPA (Opsional)')
                                ->image()
                                ->maxSize(2048)
                                ->directory('temp-logos'),
                        ]),

                    Section::make('Informasi Siswa & Periode')
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('student_name')
                                        ->label('Nama Siswa')
                                        ->default($this->student->student_name)
                                        ->required(),

                                    Select::make('semester')
                                        ->label('Semester')
                                        ->options([
                                            'Ganjil' => 'Ganjil',
                                            'Genap' => 'Genap',
                                        ])
                                        ->default('Ganjil')
                                        ->required(),

                                    TextInput::make('academic_year')
                                        ->label('Tahun Ajaran')
                                        ->default('2024/2025')
                                        ->required(),
                                ]),

                            DatePicker::make('report_date')
                                ->label('Tanggal Rapor')
                                ->default(now())
                                ->required()
                                ->format('d F Y'),
                        ]),

                    Section::make('Penilaian Jilid & Al-Qur\'an')
                        ->schema([
                            Repeater::make('quran_assessments')
                                ->label('')
                                ->schema([
                                    Grid::make(7)
                                        ->schema([
                                            TextInput::make('target')
                                                ->label('Target')
                                                ->required(),
                                            TextInput::make('achievement')
                                                ->label('Prestasi')
                                                ->required(),
                                            Select::make('m')
                                                ->label('M')
                                                ->options(['A' => 'A', 'B+' => 'B+', 'B' => 'B', 'C+' => 'C+', 'C' => 'C'])
                                                ->required(),
                                            Select::make('mad')
                                                ->label('MAD')
                                                ->options(['A' => 'A', 'B+' => 'B+', 'B' => 'B', 'C+' => 'C+', 'C' => 'C'])
                                                ->required(),
                                            Select::make('t')
                                                ->label('T')
                                                ->options(['A' => 'A', 'B+' => 'B+', 'B' => 'B', 'C+' => 'C+', 'C' => 'C'])
                                                ->required(),
                                            Select::make('k')
                                                ->label('K')
                                                ->options(['A' => 'A', 'B+' => 'B+', 'B' => 'B', 'C+' => 'C+', 'C' => 'C'])
                                                ->required(),
                                            TextInput::make('description')
                                                ->label('Keterangan')
                                                ->required(),
                                        ]),
                                ])
                                ->defaultItems(1)
                                ->addActionLabel('+ Tambah Penilaian')
                                ->reorderableWithButtons()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['target'] ?? 'Penilaian Baru'),
                        ]),

                    Section::make('Penilaian Tahfidz')
                        ->schema([
                            Repeater::make('tahfidz_assessments')
                                ->label('')
                                ->schema([
                                    Grid::make(6)
                                        ->schema([
                                            TextInput::make('target')
                                                ->label('Target Tahfidz')
                                                ->required(),
                                            Select::make('m')
                                                ->label('M')
                                                ->options(['A' => 'A', 'B+' => 'B+', 'B' => 'B', 'C+' => 'C+', 'C' => 'C'])
                                                ->required(),
                                            Select::make('mad')
                                                ->label('MAD')
                                                ->options(['A' => 'A', 'B+' => 'B+', 'B' => 'B', 'C+' => 'C+', 'C' => 'C'])
                                                ->required(),
                                            Select::make('t')
                                                ->label('T')
                                                ->options(['A' => 'A', 'B+' => 'B+', 'B' => 'B', 'C+' => 'C+', 'C' => 'C'])
                                                ->required(),
                                            Select::make('k')
                                                ->label('K')
                                                ->options(['A' => 'A', 'B+' => 'B+', 'B' => 'B', 'C+' => 'C+', 'C' => 'C'])
                                                ->required(),
                                            TextInput::make('description')
                                                ->label('Keterangan')
                                                ->required(),
                                        ]),
                                ])
                                ->defaultItems(1)
                                ->addActionLabel('+ Tambah Tahfidz')
                                ->reorderableWithButtons()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['target'] ?? 'Tahfidz Baru'),
                        ]),

                    Section::make('Penilaian Do\'a & Hadits')
                        ->schema([
                            Repeater::make('doa_hadits_assessments')
                                ->label('')
                                ->schema([
                                    Grid::make(4)
                                        ->schema([
                                            TextInput::make('target')
                                                ->label('Target Do\'a/Hadits')
                                                ->required(),
                                            Select::make('fashohah')
                                                ->label('Fashohah')
                                                ->options(['A' => 'A', 'B+' => 'B+', 'B' => 'B', 'C+' => 'C+', 'C' => 'C', '-' => '-'])
                                                ->required(),
                                            Select::make('kelancaran')
                                                ->label('Kelancaran')
                                                ->options(['A' => 'A', 'B+' => 'B+', 'B' => 'B', 'C+' => 'C+', 'C' => 'C', '-' => '-'])
                                                ->required(),
                                            TextInput::make('description')
                                                ->label('Keterangan')
                                                ->required(),
                                        ]),
                                ])
                                ->defaultItems(1)
                                ->addActionLabel('+ Tambah Do\'a/Hadits')
                                ->reorderableWithButtons()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['target'] ?? 'Do\'a/Hadits Baru'),
                        ]),
                ])
                ->action(function (array $data) {
                    $this->generatePDF($data);
                })
                ->modalWidth('7xl')
                ->slideOver(),
        ];
    }

    /**
     * @param array<int,mixed> $data
     */
    public function generatePDF(array $data): void
    {
        try {
            // Process logo if uploaded
            $logoBase64 = null;
            if (!empty($data['logo_file'])) {
                $logoPath = Storage::path($data['logo_file']);
                if (file_exists($logoPath)) {
                    $logoData = file_get_contents($logoPath);
                    $logoBase64 = base64_encode($logoData);
                    // Clean up temp file
                    Storage::delete($data['logo_file']);
                }
            }

            // Get grade aspects from existing class notes
            $gradeAspects = $this->classNoteData ?: [
                ['aspect' => 'JILID & AL-QUR\'AN', 'note' => 'Silakan isi catatan untuk aspek ini'],
                ['aspect' => 'HAFALAN', 'note' => 'Silakan isi catatan untuk aspek ini'],
                ['aspect' => 'ADAB', 'note' => 'Silakan isi catatan untuk aspek ini'],
                ['aspect' => 'KEHADIRAN', 'note' => 'Silakan isi catatan untuk aspek ini'],
            ];

            // Format report date
            $reportDate = \Carbon\Carbon::parse($data['report_date'])->format('d F Y');

            // Prepare data for PDF
            $pdfData = [
                'logo_base64' => $logoBase64,
                'institution_name' => $data['institution_name'],
                'institution_address' => $data['institution_address'],
                'student_name' => $data['student_name'],
                'semester' => $data['semester'],
                'academic_year' => $data['academic_year'],
                'quran_assessments' => $data['quran_assessments'] ?? [],
                'tahfidz_assessments' => $data['tahfidz_assessments'] ?? [],
                'doa_hadits_assessments' => $data['doa_hadits_assessments'] ?? [],
                'grade_aspects' => $gradeAspects,
                'location' => $data['location'],
                'report_date' => $reportDate,
                'teacher_name' => $data['teacher_name'],
                'principal_name' => $data['principal_name'],
            ];

            // Generate PDF
            $pdf = Pdf::loadView('pdf.tpa-report-card', $pdfData);
            $pdf->setPaper('A4', 'portrait');

            $fileName = 'Rapor_' . str_replace(' ', '_', $data['student_name']) . '_' . str_replace('/', '-', $data['academic_year']) . '.pdf';

            // Download PDF
            Notification::make()
                ->title('PDF Rapor berhasil dibuat')
                ->success()
                ->send();

            response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $fileName);

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error membuat PDF')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function saveClassNote(): void
    {
        $data = $this->form->getState();

        ClassNote::updateOrCreate(
            [
                'semester_class_id' => $this->semesterClass->id,
                'student_id' => $this->student->id,
            ],
            [
                'grade_aspects' => $data['grade_aspects'],
            ]
        );

        Notification::make()
            ->title('Catatan kelas berhasil disimpan')
            ->success()
            ->send();
    }

    public function getBreadcrumbs(): array
    {
        if ($this->navigationSource === 'student') {
            return [
                url()->route('filament.admin.resources.students.index') => 'Students',
                url()->route('filament.admin.resources.students.edit', ['record' => $this->student]) => $this->student->student_name,
                '#' => "Progres - {$this->semesterClass->nama_semester_class}",
            ];
        }

        // Default breadcrumbs for class navigation
        return [
            url()->route('filament.admin.resources.academic.semester-classes.index') => 'Kelas Semester',
            url()->route('filament.admin.resources.academic.semester-classes.edit', ['record' => $this->semesterClass]) => $this->semesterClass->nama_semester_class,
            '#' => "Progres {$this->student->student_name}",
        ];
    }
}
