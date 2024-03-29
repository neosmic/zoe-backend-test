<?php

namespace Database\Factories;

use App\Models\SecurityType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Security>
 */
class SecurityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'symbol' => $this->faker->unique()->regexify('[A-Z]{3,5}'),
            'security_type_id' => function () {
                return SecurityType::inRandomOrder()->first()->id;
            },
        ];
    }
}
