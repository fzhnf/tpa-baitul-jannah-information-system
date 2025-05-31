<?php

namespace App\Filament\Resources\Academic\StudentResource\Pages;

use App\Filament\Resources\Academic\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Murid'),
        ];
    }
}
