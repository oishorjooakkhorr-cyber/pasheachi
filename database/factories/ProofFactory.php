<?php

namespace Database\Factories;

use App\Models\Errand;
use App\Models\Proof;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Proof>
 */
class ProofFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'errand_id' => Errand::factory()->accepted(),
            'type' => fake()->randomElement(['photo', 'receipt']),
            'file_path' => 'proofs/'.Str::uuid().'.jpg',
            'note' => fake()->optional()->sentence(),
            'uploaded_at' => now(),
        ];
    }
}
