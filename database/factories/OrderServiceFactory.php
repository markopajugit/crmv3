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
            'order_id' => function () {
                return Order::inRandomOrder()->first()?->id ?? Order::factory()->create()->id;
            },
            'service_id' => function () {
                $service = Service::inRandomOrder()->first();
                if (!$service) {
                    // If no services exist, create one manually
                    $service = Service::create([
                        'name' => 'Default Service',
                        'cost' => '0',
                        'type' => 'regular',
                    ]);
                }
                return $service->id;
            },
            'name' => $this->faker->words(3, true),
            'cost' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
