<?php

namespace App\Filament\Resources\Academic\StudentResource\Pages;

use App\Filament\Resources\Academic\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Murid'),
        ];
    }
}
