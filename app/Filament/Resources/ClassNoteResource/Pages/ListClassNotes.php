<?php

namespace App\Filament\Resources\ClassNoteResource\Pages;

use App\Filament\Resources\ClassNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassNotes extends ListRecords
{
    protected static string $resource = ClassNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
