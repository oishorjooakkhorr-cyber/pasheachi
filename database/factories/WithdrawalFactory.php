<?php

namespace Database\Factories;

use App\Enums\WithdrawalStatus;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Withdrawal>
 */
class WithdrawalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'runner_id' => User::factory()->runner(),
            'amount' => fake()->numberBetween(5, 50) * 100,
            'bkash_number' => fake()->numerify('01#########'),
            'status' => WithdrawalStatus::Requested,
            'requested_at' => now(),
        ];
    }
}
