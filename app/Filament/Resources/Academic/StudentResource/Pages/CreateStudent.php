<?php

namespace App\Filament\Resources\Academic\StudentResource\Pages;

use App\Filament\Resources\Academic\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    public function getTitle(): string
    {
        return 'Tambah Murid';
    }
}
