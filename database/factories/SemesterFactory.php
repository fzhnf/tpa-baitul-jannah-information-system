<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class SemesterFactory extends Factory
{
    public function definition(): array
    {
        $startYear = $this->faker->numberBetween(now()->subYears(5)->year, now()->year);
        $schoolYear = $startYear.'/'.($startYear + 1);

        return [
            'school_year' => $schoolYear,
            'semester_enum' => $this->faker->randomElement([1, 2]), // Assuming enum values 1=Odd, 2=Even
        ];
    }
}
