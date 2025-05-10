<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\Semester;
use App\Models\SemesterClass;
use App\Models\Student;
use App\Models\StudentAchievement;
use Illuminate\Database\Seeder;

class AchievementsTableSeeder extends Seeder
{
    public function run(): void
    {

        $csvFile = fopen(base_path('database/data/The Quran Dataset_trimmed.csv'), 'r');
        fgetcsv($csvFile); // skip header

        $chunkSize = 50;
        $achievementData = [];
        $count = 0;

        \DB::beginTransaction();
        try {
            while (($data = fgetcsv($csvFile)) !== false) {
                $achievementData[] = [
                    'achievement_name' => "Q.S. {$data[1]}/{$data[0]}: {$data[2]} (Juz {$data[3]})",
                    'category' => 'tahfidz',
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

            if (! empty($achievementData)) {
                Achievement::insert($achievementData);
            }
            fclose($csvFile);

            // Create 10 students
            $students = Student::factory()->count(10)->create();
            $studentIds = $students->pluck('id')->toArray();

            // Create semesters
            $semesters = [
                Semester::create(['school_year' => '2023/2024', 'semester_enum' => 1]),
                Semester::create(['school_year' => '2023/2024', 'semester_enum' => 2]),
                Semester::create(['school_year' => '2024/2025', 'semester_enum' => 1]),
            ];

            // Create classes for each semester
            $classes = [];
            foreach ($semesters as $semester) {
                $semesterClasses = SemesterClass::factory(5)->create(['semester_id' => $semester->id]);
                $classes = array_merge($classes, $semesterClasses->toArray());
            }

            // Create class sessions for each class
            $classSessions = [];
            foreach ($classes as $class) {
                $sessions = ClassSession::factory(3)->create(['semester_class_id' => $class['id']]);
                $classSessions = array_merge($classSessions, $sessions->toArray());
            }
            $classSessionIds = array_column($classSessions, 'id');

            // Get existing achievements
            $achievementIds = Achievement::pluck('id')->toArray();

            // Create attendances
            foreach ($classSessionIds as $sessionId) {
                foreach ($studentIds as $studentId) {
                    if (rand(0, 10) > 2) { // 80% chance of having attendance
                        Attendance::create([
                            'class_session_id' => $sessionId,
                            'student_id' => $studentId,
                            'status' => fake()->randomElement(['hadir', 'sakit', 'ijin', 'absen']),
                            'remarks' => fake()->sentence(),
                        ]);
                    }
                }
            }

            // Student achievements
            $studentAchievementData = [];
            foreach (range(1, 30) as $_) {
                $studentAchievementData[] = [
                    'student_id' => fake()->randomElement($studentIds),
                    'achievement_id' => fake()->randomElement($achievementIds),
                    'class_session_id' => fake()->randomElement($classSessionIds),
                    'tanggal' => fake()->date(),
                    'keterangan' => fake()->sentence(),
                    'catatan' => fake()->paragraph(),
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

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }
}
