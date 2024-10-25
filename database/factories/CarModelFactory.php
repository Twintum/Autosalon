<?php

namespace Database\Factories;

use App\Models\CarModel;
use App\Models\Mark;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarModel>
 */
class CarModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'model' => $this->faker->word,
            'price' => $this->faker->numberBetween(10000, 100000),
            'photo' => $this->faker->imageUrl(),
            'mark_id' => $this->faker->numberBetween(1, 2),
            'transmission' => $this->faker->randomElement(['mechanic', 'automatic', 'robot']),
            'drive' => $this->faker->randomElement(['FWD', 'RWD', 'AWD']),
            'fuel_tank' => $this->faker->numberBetween(40, 80),
            'color' => $this->faker->colorName,
            'mileage' => $this->faker->numberBetween(0, 200000),
            'discount' => $this->faker->numberBetween(0, 50),
            'year' => $this->faker->year,
            'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
