<?php

namespace App\Filament\Resources\Academic\AttendanceResource\Pages;

use App\Filament\Resources\Academic\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
}
