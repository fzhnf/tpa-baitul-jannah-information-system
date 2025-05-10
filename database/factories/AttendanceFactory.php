<?php

namespace Database\Factories;

use App\Models\ClassSession;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class AttendanceFactory extends Factory
{
    /**
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        return [
            'class_session_id' => ClassSession::factory(),
            'student_id' => Student::factory(),
            'status' => fake()->randomElement(['present', 'absent', 'late']),
            'remarks' => fake()->sentence(),
        ];
    }
}
