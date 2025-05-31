<?php

namespace App\Filament\Resources\Academic\ClassSessionResource\Pages;

use App\Filament\Resources\Academic\ClassSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassSession extends EditRecord
{
    protected static string $resource = ClassSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Sesi'),
        ];
    }
}
