<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = [
            ['name' => 'Cash on Delivery', 'icon' => 'banknote'],
            ['name' => 'Express Shipping', 'icon' => 'truck'],
            ['name' => 'Free Delivery', 'icon' => 'box'],
            ['name' => '7 Days Replacement', 'icon' => 'refresh-cw'],
            ['name' => '2-Year Warranty', 'icon' => 'shield-check'],
            ['name' => '24/7 Support', 'icon' => 'headset'],
        ];

        $service = fake()->randomElement($services);

        return [
            'vendor_id' => User::factory(),
            'name' => $service['name'],
            'icon' => $service['icon'],
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
