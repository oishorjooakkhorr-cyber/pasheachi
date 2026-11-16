<?php

namespace Database\Factories;

use App\Enums\ErrandStatus;
use App\Models\Errand;
use App\Models\ErrandCategory;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * A posted errand with no runner yet. The client comes from the family
 * member and the service fee from the category, so they always match.
 *
 * @extends Factory<Errand>
 */
class ErrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'family_member_id' => FamilyMember::factory(),
            'client_id' => fn (array $attributes) => FamilyMember::find($attributes['family_member_id'])->client_id,
            'errand_category_id' => ErrandCategory::factory(),
            'service_fee' => fn (array $attributes) => ErrandCategory::find($attributes['errand_category_id'])->base_service_fee,
            'runner_id' => null,
            'description' => fake()->sentence(10),
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+2 weeks'),
            'status' => ErrandStatus::Posted,
            'is_urgent' => false,
            'item_cost' => fake()->numberBetween(0, 30) * 100,
            'priority_fee' => 0,
        ];
    }

    /**
     * Assigned to a runner in the family member's district.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ErrandStatus::Accepted,
            'runner_id' => fn (array $attributes) => User::factory()->runner(FamilyMember::find($attributes['family_member_id'])->district),
        ]);
    }

    /**
     * Done and confirmed by the family OTP.
     */
    public function completed(): static
    {
        return $this->accepted()->state(fn (array $attributes) => [
            'status' => ErrandStatus::Completed,
            'scheduled_at' => fake()->dateTimeBetween('-2 weeks', '-1 day'),
            'completed_at' => now(),
        ]);
    }
}
