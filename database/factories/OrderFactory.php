<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Order;
use App\Models\User;
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
            'name' => $this->faker->word(),
            'description' => $this->faker->text(50),
            'notes' => $this->faker->text(),
            'status' => $this->faker->randomElement(['Not Active' ,'Active', 'Finished']),
            'payment_status' => $this->faker->randomElement(['Paid', 'Not paid']),
            'awaiting_status' => $this->faker->randomElement(['Waiting action from Client', 'Waiting action from us']),
            'notification_sent' => false,
            'responsible_user_id' => function () {
                return User::inRandomOrder()->first()->id ?? User::factory()->create()->id;
            },
            'company_id' => function () {
                return Company::inRandomOrder()->first()?->id ?? Company::factory()->create()->id;
            },
        ];
    }
}
