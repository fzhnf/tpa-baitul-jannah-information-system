<?php

namespace App\Filament\Resources\Academic\AchievementResource\Pages;

use App\Filament\Resources\Academic\AchievementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAchievement extends EditRecord
{
    protected static string $resource = AchievementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Pencapaian'),
        ];
    }
}
