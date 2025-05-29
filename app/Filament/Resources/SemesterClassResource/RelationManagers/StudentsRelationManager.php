<?php

namespace App\Filament\Resources\SemesterClassResource\RelationManagers;

use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';
    protected static ?string $title = 'Murid';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Murid'),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255)
                    ->label('Alamat'),
                Forms\Components\TextInput::make('guardian')
                    ->label('Orang tua/ wali murid')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('entry_date')->label('Tanggal masuk'),
                Forms\Components\FileUpload::make('profile_picture_url')
                    ->image()
                    ->directory('student-profiles')
                    ->label('Foto profil'),
                Forms\Components\TextInput::make('guardian_number')
                    ->tel()
                    ->maxLength(255)
                    ->label('Nomor telepon orang tua/ wali murid'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('student_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Murid'),
                Tables\Columns\ImageColumn::make('profile_picture_url')
                    ->label('Foto Profile')
                    ->circular(),
                Tables\Columns\TextColumn::make('guardian')
                    ->searchable()
                    ->label('Orang Tua / Wali Murid'),
                Tables\Columns\TextColumn::make('guardian_number')
                    ->label('Nomor Orang Tua / Wali Murid'),
                Tables\Columns\TextColumn::make('entry_date')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Masuk'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Murid Baru'),
                Tables\Actions\AttachAction::make()
                    ->label('Masukkan Murid'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_progression')
                    ->label('Lihat Progres')
                    ->icon('heroicon-o-chart-bar')
                    ->color('success')
                    ->url(fn (Student $record): string => route('filament.admin.resources.semester-classes.student-progression', [
                        'semesterClass' => $this->getOwnerRecord()->id,
                        'student' => $record->id,
                    ])),
                Tables\Actions\EditAction::make()
                    ->label('Perbaharui'),
                Tables\Actions\DetachAction::make()
                    ->label('Keluarkan Murid'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
