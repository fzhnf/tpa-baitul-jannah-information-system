<?php

namespace Database\Factories\Academic;

use App\Models\Academic\Achievement;
use App\Models\Academic\ClassSession;
use App\Models\Academic\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class StudentAchievementFactory extends Factory
{
    /**
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'class_session_id' => ClassSession::factory(),
            'achievement_id' => Achievement::factory(),
            'tanggal' => fake()->date(),
            'keterangan' => fake()->sentence(),
            'makruj' => fake()->numberBetween(1, 5),
            'mad' => fake()->numberBetween(1, 5),
            'tajwid' => fake()->numberBetween(1, 5),
            'kelancaran' => fake()->numberBetween(1, 5),
            'fashohah' => fake()->numberBetween(1, 5),
        ];
    }
}
