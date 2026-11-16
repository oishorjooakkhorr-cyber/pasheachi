<?php

namespace Database\Factories;

use App\Enums\DisputeStatus;
use App\Models\Dispute;
use App\Models\Errand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * An open dispute about a completed errand.
 *
 * @extends Factory<Dispute>
 */
class DisputeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'errand_id' => Errand::factory()->completed(),
            'reason' => fake()->randomElement(['Wrong item', 'Task not done', 'Late arrival']),
            'description' => fake()->paragraph(),
            'status' => DisputeStatus::Open,
        ];
    }
}
