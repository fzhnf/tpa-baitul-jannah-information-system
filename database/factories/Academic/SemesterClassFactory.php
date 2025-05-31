<?php

namespace Database\Factories\Academic;

use App\Models\Academic\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class SemesterClassFactory extends Factory
{
    /**
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        return [
            'semester_id' => Semester::factory(),
            'nama_semester_class' => fake()->sentence(),
        ];
    }
}
