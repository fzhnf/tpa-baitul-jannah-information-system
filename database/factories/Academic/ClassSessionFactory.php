<?php

namespace Database\Factories\Academic;

use App\Models\Academic\SemesterClass;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class ClassSessionFactory extends Factory
{
    /**
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        return [
            'semester_class_id' => SemesterClass::factory(),
            'date' => fake()->dateTimeBetween('-1 year', '+1 year'),
            'description' => fake()->sentence(),
        ];
    }
}
