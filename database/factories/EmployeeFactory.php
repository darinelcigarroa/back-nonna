<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $position = Position::inRandomOrder()->first();

        return [
            'name' => $this->faker->name(),
            'first_surname' => $this->faker->firstName(),
            'second_surname' => $this->faker->lastName(),
            'position_id' => $position->id,
            'salary' => $this->faker->numberBetween($min = 1000, $max = 9000),
        ];
    }
}
