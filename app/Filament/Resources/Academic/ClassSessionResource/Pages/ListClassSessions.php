<?php

namespace App\Filament\Resources\Academic\ClassSessionResource\Pages;

use App\Filament\Resources\Academic\ClassSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassSessions extends ListRecords
{
    protected static string $resource = ClassSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Sesi Baru'),
        ];
    }
}
