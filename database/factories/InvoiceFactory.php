<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => $this->faker->numberBetween(1, Order::count()),
            //'status' => $this->faker->randomElement(['paid', 'not paid']),
            'issue_date' => $this->faker->date('Y-m-d'),
            'payment_date' => $this->faker->date('Y-m-d'),
            'vat' => $this->faker->randomElement(['0', '21']),
            'number' => $this->faker->numerify('INV-####')
        ];
    }
}
