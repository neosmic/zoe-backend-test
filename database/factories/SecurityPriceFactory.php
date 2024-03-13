<?php

namespace Database\Factories;

use App\Models\Security;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecurityPrice>
 */
class SecurityPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'security_id' => function () {
                return Security::inRandomOrder()->first()->id;
            },
            'last_price' => $this->faker->randomFloat(2, 1, 1000), // Adjust range as needed
            'as_of_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
