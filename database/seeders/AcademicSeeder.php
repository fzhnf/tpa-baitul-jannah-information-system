<?php

namespace Database\Seeders;

use App\Models\Academic\Achievement;
use App\Models\Academic\Attendance;
use App\Models\Academic\ClassSession;
use App\Models\Academic\Semester;
use App\Models\Academic\SemesterClass;
use App\Models\Academic\Student;
use App\Models\Academic\StudentAchievement;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        // Import Tahfidz achievements from CSV
        $csvFile = fopen(base_path('database/data/quran_dataset_trimmed.csv'), 'r');
        fgetcsv($csvFile); // skip header

        $chunkSize = 50;
        $achievementData = [];
        $count = 0;

        // Process Tahfidz achievements
        while (($data = fgetcsv($csvFile)) !== false) {
            $achievementData[] = [
                'achievement_name' => "Q.S. {$data[1]}/{$data[0]}: {$data[2]}",
                'category' => 'Tahfidz',
                'module' => "Juz {$data[3]}",
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $count++;

            if ($count % $chunkSize === 0) {
                Achievement::insert($achievementData);
                $achievementData = [];
                if ($count % ($chunkSize * 10) === 0) {
                    gc_collect_cycles();
                }
            }
        }

        if (!empty($achievementData)) {
            Achievement::insert($achievementData);
        }
        fclose($csvFile);

        // Create Ummi achievements
        $ummiAchievements = [];
        $doaHadistAchievements = [];

        foreach (range(1, 10) as $module) {
            $ummiAchievements[] = [
                'achievement_name' => "Ummi Module $module",
                'category' => 'Ummi',
                'module' => "Jilid $module",
                'created_at' => now(),
                'updated_at' => now(),
            ];
            // Doa modules
            $doaHadistAchievements[] = [
                'achievement_name' => "Doa Harian $module",
                'category' => 'Doa & Hadist',
                'module' => "Modul $module",
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $doaHadistAchievements[] = [
                'achievement_name' => "Hadist Pendek $module",
                'category' => 'Doa & Hadist',
                'module' => "Modul $module",
                'created_at' => now(),
                'updated_at' => now(),
            ];

        }

        Achievement::insert($ummiAchievements);
        Achievement::insert($doaHadistAchievements);

        // Create 10 students
        $students = Student::factory()->count(10)->create();
        $studentIds = $students->pluck('id')->toArray();

        // Create semesters
        $semesters = [
            Semester::create(['school_year' => '2023/2024', 'semester_enum' => 1]),
            Semester::create(['school_year' => '2023/2024', 'semester_enum' => 2]),
            Semester::create(['school_year' => '2024/2025', 'semester_enum' => 1]),
        ];

        // Create classes for each semester and assign students
        $classes = [];
        foreach ($semesters as $semester) {
            $semesterClasses = SemesterClass::factory(5)->create(['semester_id' => $semester->id]);

            foreach ($semesterClasses as $class) {
                // Assign 3-8 random students to each class
                $randomStudentIds = fake()->randomElements(
                    $studentIds,
                    fake()->numberBetween(3, min(8, count($studentIds)))
                );
                $class->students()->attach($randomStudentIds);
            }

            $classes = array_merge($classes, $semesterClasses->toArray());
        }

        // Create class sessions for each class
        $classSessions = [];
        foreach ($classes as $class) {
            $sessions = ClassSession::factory(3)->create(['semester_class_id' => $class['id']]);
            $classSessions = array_merge($classSessions, $sessions->toArray());
        }

        // Get existing achievements
        $achievementIds = Achievement::pluck('id')->toArray();

        // Create attendances only for enrolled students
        foreach ($classSessions as $session) {
            $class = SemesterClass::find($session['semester_class_id']);
            $enrolledStudentIds = $class->students()->pluck('id')->toArray();

            foreach ($enrolledStudentIds as $studentId) {
                if (rand(0, 10) > 2) { // 80% chance of attendance
                    Attendance::create([
                        'class_session_id' => $session['id'],
                        'student_id' => $studentId,
                        'status' => fake()->randomElement(['hadir', 'sakit', 'ijin', 'absen']),
                        'remarks' => fake()->sentence(),
                    ]);
                }
            }
        }

        // Create student achievements
        $studentAchievementData = [];
        foreach (range(1, 30) as $_) {
            $studentAchievementData[] = [
                'student_id' => fake()->randomElement($studentIds),
                'achievement_id' => fake()->randomElement($achievementIds),
                'class_session_id' => fake()->randomElement(array_column($classSessions, 'id')),
                'tanggal' => fake()->date(),
                'keterangan' => fake()->sentence(),
                'makruj' => fake()->numberBetween(1, 5),
                'mad' => fake()->numberBetween(1, 5),
                'tajwid' => fake()->numberBetween(1, 5),
                'kelancaran' => fake()->numberBetween(1, 5),
                'fashohah' => fake()->numberBetween(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        StudentAchievement::insert($studentAchievementData);
    }
}
