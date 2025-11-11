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
            'person_id' => $this->faker->numberBetween(1, Person::count()),
            'company_id' => $this->faker->numberBetween(1, Company::count()),
            'relation' => $this->faker->randomElement(['Board Memeber', 'Shareholder', 'Agent']),
            'selected_email' => $this->faker->safeEmail()
        ];
    }
}
