<?php

namespace App\Filament\Resources\Academic\SemesterClassResource\Pages;

use App\Filament\Resources\Academic\SemesterClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSemesterClasses extends ListRecords
{
    protected static string $resource = SemesterClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Kelas Baru'),
        ];
    }
}
