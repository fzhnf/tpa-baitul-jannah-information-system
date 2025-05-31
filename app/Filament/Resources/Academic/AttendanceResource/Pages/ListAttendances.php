<?php

namespace App\Filament\Resources\Academic\AttendanceResource\Pages;

use App\Filament\Resources\Academic\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Absensi Baru'),
        ];
    }
}
