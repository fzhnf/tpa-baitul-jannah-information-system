<?php

namespace App\Filament\Resources\Academic\StudentAchievementResource\Pages;

use App\Filament\Resources\Academic\StudentAchievementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentAchievements extends ListRecords
{
    protected static string $resource = StudentAchievementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pencapaian Murid'),
        ];
    }
}
