<?php

use App\Filament\Resources\SemesterClassResource\Pages\StudentProgression;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get(
    '/students/{student}/classes/{semesterClass}/progression',
    [StudentProgression::class, 'mountFromStudent']
)
    ->name('filament.admin.resources.students.progression');
