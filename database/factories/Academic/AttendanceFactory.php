<?php

namespace Database\Factories\Academic;

use App\Models\Academic\Student;
use App\Models\Academic\ClassSession;
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
            'status' => fake()->randomElement(['hadir', 'sakit', 'ijin', 'absen']),
            'remarks' => fake()->sentence(),
        ];
    }
}
