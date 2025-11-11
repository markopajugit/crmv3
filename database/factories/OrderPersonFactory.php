<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Person;
use App\Models\OrderPerson;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderPersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderPerson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'person_id' => $this->faker->numberBetween(1, Person::count()),
            'order_id' => $this->faker->numberBetween(1, Order::count())
        ];
    }
}
