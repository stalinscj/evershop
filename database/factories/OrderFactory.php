<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_name'   => $this->faker->name,
            'customer_email'  => $this->faker->email,
            'customer_mobile' => $this->faker->numerify('###########'),
            'status'          => Order::STATUS_CREATED,
        ];
    }

    /**
     * Indicate that the model's status should be payed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function payed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Order::STATUS_PAYED,
            ];
        });
    }

    /**
     * Indicate that the model's status should be rejected.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Order::STATUS_REJECTED,
            ];
        });
    }
}
