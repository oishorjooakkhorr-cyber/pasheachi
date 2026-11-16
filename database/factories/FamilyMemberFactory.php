<?php

namespace Database\Factories;

use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FamilyMember>
 */
class FamilyMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => User::factory()->client(),
            'name' => fake()->name(),
            'relation' => fake()->randomElement(['Mother', 'Father', 'Grandmother', 'Grandfather', 'Uncle', 'Aunt', 'Sister', 'Brother']),
            'phone' => fake()->numerify('01#########'),
            'address' => fake()->streetAddress(),
            'district' => fake()->randomElement(config('districts')),
            'care_notes' => fake()->optional()->randomElement([
                'Diabetic, needs insulin from the pharmacy.',
                'Uses a wheelchair.',
                'Hard of hearing, please speak clearly.',
                'Has a check-up every month.',
            ]),
        ];
    }
}
