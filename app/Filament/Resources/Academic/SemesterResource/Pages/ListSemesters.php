<?php

namespace App\Filament\Resources\Academic\SemesterResource\Pages;

use App\Filament\Resources\Academic\SemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSemesters extends ListRecords
{
    protected static string $resource = SemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Semester Baru'),
        ];
    }
}
