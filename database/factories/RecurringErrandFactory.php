<?php

namespace Database\Factories;

use App\Models\ErrandCategory;
use App\Models\FamilyMember;
use App\Models\RecurringErrand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecurringErrand>
 */
class RecurringErrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * The client is taken from the family member, so the two always match.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dayOfMonth = fake()->numberBetween(1, 28);

        return [
            'family_member_id' => FamilyMember::factory(),
            'client_id' => fn (array $attributes) => FamilyMember::find($attributes['family_member_id'])->client_id,
            'errand_category_id' => ErrandCategory::factory(),
            'day_of_month' => $dayOfMonth,
            'estimated_item_cost' => fake()->numberBetween(0, 20) * 100,
            'next_run_date' => now()->addMonthNoOverflow()->day($dayOfMonth)->toDateString(),
            'is_active' => true,
        ];
    }
}
