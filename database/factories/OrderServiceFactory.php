<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderService;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderService::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => $this->faker->numberBetween(1, Order::count()),
            'service_id' => $this->faker->numberBetween(1, Service::count()),
        ];
    }
}
