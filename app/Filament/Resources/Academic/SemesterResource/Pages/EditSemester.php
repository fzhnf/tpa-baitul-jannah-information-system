<?php

namespace App\Filament\Resources\Academic\SemesterResource\Pages;

use App\Filament\Resources\Academic\SemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSemester extends EditRecord
{
    protected static string $resource = SemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Semester'),
        ];
    }
}
