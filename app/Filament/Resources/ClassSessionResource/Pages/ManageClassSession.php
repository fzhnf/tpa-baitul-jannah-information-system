<?php

namespace App\Filament\Resources\ClassSessionResource\Pages;

use App\Filament\Resources\ClassSessionResource;
use App\Models\ClassSession;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables;
use Filament\Resources\Pages\Page;

class ManageClassSession extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = ClassSessionResource::class;

    protected static string $view = 'filament.resources.class-session-resource.pages.manage-class-session';

    public ClassSession $record;

    public function mount(ClassSession $record): void
    {
        $this->record = $record;
    }

    protected function getTableQuery()
    {
        // Ambil student dari semesterClass terkait
        return $this->record->semesterClass->students()->getQuery();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('student_name')->label('Nama Siswa'),
            Tables\Columns\TextColumn::make('address')->label('Alamat'),
            Tables\Columns\TextColumn::make('guardian')->label('Wali Murid'),
        ];
    }
}
