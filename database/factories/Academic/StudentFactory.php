<?php

namespace Database\Factories\Academic;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_name' => $this->faker->name(),
            'address' => $this->faker->streetAddress(),
            'guardian' => $this->faker->name(),
            'entry_date' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'profile_picture_url' => $this->faker->imageUrl(200, 200, 'people'),
            'guardian_number' => $this->faker->numerify('08##########'), // Indonesian-style phone number
        ];
    }
}
