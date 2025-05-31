<?php

namespace App\Filament\Resources\Academic\SemesterClassResource\Pages;

use App\Filament\Resources\Academic\SemesterClassResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSemesterClass extends EditRecord
{
    protected static string $resource = SemesterClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Kelas'),
        ];
    }
}
