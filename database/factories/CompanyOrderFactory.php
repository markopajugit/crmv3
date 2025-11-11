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
            'company_id' => $this->faker->numberBetween(1, Company::count()),
            'order_id' => $this->faker->unique()->numberBetween(1, Order::count()),
            'responsible_user_id' => $this->faker->numberBetween(1, 2)
        ];
    }
}
