{{-- resources/views/filament/resources/semester-class-resource/pages/student-progression.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Student Info Card --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center space-x-4">
                @if($this->student->profile_picture_url)
                    <img src="{{ Storage::url($this->student->profile_picture_url) }}"
                         alt="{{ $this->student->student_name }}"
                         class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $this->student->student_name }}</h2>
                    <p class="text-sm text-gray-600">Kelas: {{ $this->semesterClass->nama_semester_class }}</p>
                    <p class="text-sm text-gray-600">Semester: {{ $this->semesterClass->semester->name ?? 'N/A' }}</p>
                    @if($this->student->guardian)
                        <p class="text-sm text-gray-600">Wali: {{ $this->student->guardian }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Achievement Progress Table --}}
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Riwayat Pencapaian</h2>
            {{ $this->table }}
        </div>

        {{-- Class Notes Form --}}
        <div class="mb-8">
            <form wire:submit="saveClassNote">
                {{ $this->form }}
            </form>
        </div>

        {{-- Footer Actions --}}
        <div class="flex justify-center pt-6 border-t border-gray-200">
            <x-filament::button
                color="primary"
                icon="heroicon-o-printer"
                wire:click="$dispatch('printPDF')"
            >
                Cetak Laporan PDF
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>

