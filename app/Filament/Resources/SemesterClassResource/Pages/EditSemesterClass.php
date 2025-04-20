<?php

namespace App\Filament\Resources\SemesterClassResource\Pages;

use App\Filament\Resources\SemesterClassResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSemesterClass extends EditRecord
{
    protected static string $resource = SemesterClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
