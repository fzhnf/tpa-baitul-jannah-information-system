<x-filament::page>
    <x-filament::card>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">
                Class: {{ $record->semesterClass->nama_semester_class }}
            </h2>
            <h3 class="text-lg">
                tanggal: {{ $record->date->format('F j, Y H:i') }}
            </h3>
        </div>

        <div class="mb-4">
            <p class="text-gray-600">
                @if($record->description)
                    {{ $record->description }}
                @else
                    No description provided for this session.
                @endif
            </p>
        </div>

        <h3 class="text-xl font-semibold mb-2">Students in this Class</h3>

        {{ $studentsTable }}
    </x-filament::card>
</x-filament::page>
