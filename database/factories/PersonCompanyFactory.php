<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\PersonCompany;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonCompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonCompany::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'person_id' => function () {
                return Person::inRandomOrder()->first()?->id ?? Person::factory()->create()->id;
            },
            'company_id' => function () {
                return Company::inRandomOrder()->first()?->id ?? Company::factory()->create()->id;
            },
            'relation' => $this->faker->randomElement(['Board Memeber', 'Shareholder', 'Agent']),
            'selected_email' => $this->faker->email(),
        ];
    }
}
