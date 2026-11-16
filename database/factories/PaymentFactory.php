<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Errand;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * A payment held in escrow, in US dollars at a fixed sample rate.
 *
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amountBdt = fake()->numberBetween(5, 40) * 100;
        $rate = 122.0;

        return [
            'errand_id' => Errand::factory(),
            'amount_bdt' => $amountBdt,
            'currency' => 'USD',
            'exchange_rate' => $rate,
            'amount_foreign' => round($amountBdt / $rate, 2),
            'commission' => 0,
            'status' => PaymentStatus::Held,
            'paid_at' => now(),
        ];
    }
}
