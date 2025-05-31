<?php

namespace App\Filament\Resources\Academic\AttendanceResource\Pages;

use App\Filament\Resources\Academic\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Absensi'),
        ];
    }
}
