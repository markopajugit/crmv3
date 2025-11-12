<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyOrder;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => function () {
                return Company::inRandomOrder()->first()?->id ?? Company::factory()->create()->id;
            },
            'order_id' => function () {
                return Order::inRandomOrder()->first()?->id ?? Order::factory()->create()->id;
            },
        ];
    }
}
