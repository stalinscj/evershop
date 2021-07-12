<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderPayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id'    => Order::factory(),
            'request_id'  => $this->faker->randomNumber(2),
            'process_url' => $this->faker->url,
        ];
    }
}
