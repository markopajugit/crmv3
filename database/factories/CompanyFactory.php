<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $country = $this->faker->country();
        return [
            'name' => $this->faker->company(),
            'registry_code' => $this->faker->postcode(),
            'registration_country' => $country,
            'registration_country_abbr' => substr($country, 0, 3),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'vat' => $this->faker->postcode(),
            'notes' => $this->faker->text(),
        ];
    }
}
