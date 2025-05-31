<?php

namespace App\Filament\Resources\Academic\AchievementResource\Pages;

use App\Filament\Resources\Academic\AchievementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAchievements extends ListRecords
{
    protected static string $resource = AchievementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Pencapaian Baru'),
        ];
    }
}
