<?php

namespace Database\Factories;

use App\Enums\TrustLevel;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Fake users for tests and seeders. A plain User::factory()->create() makes
 * a client; use ->runner() or ->admin() for the other roles.
 *
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => UserRole::Client,
            'phone' => fake()->numerify('+1##########'),
            'country' => fake()->randomElement(['United States', 'Saudi Arabia', 'United Kingdom', 'United Arab Emirates', 'Malaysia']),
            'preferred_currency' => fake()->randomElement(['USD', 'SAR', 'GBP', 'AED', 'MYR']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * A client (the default state, for readability).
     */
    public function client(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Client,
        ]);
    }

    /**
     * An approved Bronze runner with a Bangladeshi phone number.
     */
    public function runner(?string $district = null): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Runner,
            'phone' => fake()->numerify('01#########'),
            'country' => null,
            'preferred_currency' => null,
            'district' => $district ?? fake()->randomElement(config('districts')),
            'nid_document' => 'runner-documents/nid-'.Str::uuid().'.pdf',
            'profile_photo' => 'runner-documents/photo-'.Str::uuid().'.jpg',
            'police_clearance' => 'runner-documents/police-'.Str::uuid().'.pdf',
            'verification_status' => VerificationStatus::Approved,
            'trust_level' => TrustLevel::Bronze,
            'balance' => 0,
            'verified_at' => now(),
        ]);
    }

    /**
     * A runner whose documents an admin has not checked yet. Use after runner().
     */
    public function pendingVerification(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => VerificationStatus::Pending,
            'verified_at' => null,
        ]);
    }

    /**
     * Set a runner's trust level. Use after runner().
     */
    public function trustLevel(TrustLevel $level): static
    {
        return $this->state(fn (array $attributes) => [
            'trust_level' => $level,
        ]);
    }

    /**
     * Platform staff.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Admin,
            'country' => null,
            'preferred_currency' => null,
        ]);
    }
}
