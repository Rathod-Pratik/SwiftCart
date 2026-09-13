<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = strtoupper($this->faker->unique()->bothify('???###'));

        return [
            'code' => $code,
            'discount_amount' => $this->faker->randomFloat(2, 0, 100),
            'discount_type' => $this->faker->randomElement(['percentage', 'fixed']),
            'number_of_coupons' => $this->faker->randomNumber(2),
        ];
    }
}
