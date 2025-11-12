<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => function () {
                return Order::inRandomOrder()->first()?->id ?? Order::factory()->create()->id;
            },
            //'status' => $this->faker->randomElement(['paid', 'not paid']),
            'issue_date' => $this->faker->date('Y-m-d'),
            'payment_date' => $this->faker->date('Y-m-d'),
            'vat' => $this->faker->randomElement(['0', '21']),
            'is_proforma' => $this->faker->boolean(),
            'number' => $this->faker->unique()->numerify('INV-####'),
        ];
    }
}
