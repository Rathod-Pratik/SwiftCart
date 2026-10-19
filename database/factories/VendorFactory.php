<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'store_name' => fake()->company(),
            'phone' => fake()->phoneNumber(),
            'business_address' => fake()->address(),
            'business_type' => fake()->randomElement(['individual', 'registered business']),
            'status' => 'pending',
            'description' => fake()->paragraph(),
            'logo' => null,
            'business_registration_number' => fake()->optional()->bothify('BRN-#####-????'),
            'tax_identification_number' => fake()->optional()->bothify('TIN-#####-????'),
            'verification_document' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }
}
