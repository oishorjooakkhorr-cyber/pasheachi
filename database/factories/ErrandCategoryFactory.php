<?php

namespace Database\Factories;

use App\Models\ErrandCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ErrandCategory>
 */
class ErrandCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->words(3, true)),
            'base_service_fee' => fake()->numberBetween(2, 8) * 100,
            'is_active' => true,
        ];
    }

    /**
     * A category the admin has deactivated (F19).
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
