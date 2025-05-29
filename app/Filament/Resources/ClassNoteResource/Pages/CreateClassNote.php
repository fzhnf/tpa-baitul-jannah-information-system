<?php

namespace App\Filament\Resources\ClassNoteResource\Pages;

use App\Filament\Resources\ClassNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClassNote extends CreateRecord
{
    protected static string $resource = ClassNoteResource::class;
}
