<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
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
        return [
            'order_id' => Order::factory(),
            'user_id' => User::factory(),
            'payment_method' => 'razorpay',
            'payment_status' => fake()->randomElement(['created', 'authorized', 'captured', 'failed', 'refunded']),
            'amount' => fake()->randomFloat(2, 100, 10000),
            'currency' => 'INR',
            'razorpay_order_id' => fake()->optional()->bothify('order_????????????'),
            'razorpay_payment_id' => fake()->unique()->bothify('pay_????????????'),
            'razorpay_signature' => fake()->optional()->sha256(),
            'refund_status' => null,
            'refund_reason' => null,
        ];
    }
}
