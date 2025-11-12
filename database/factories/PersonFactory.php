<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address_street' => $this->faker->streetAddress(),
            'address_city' => $this->faker->city(),
            'address_zip' => $this->faker->postcode(),
            'address_dropdown' => $this->faker->country(),
            'id_code' => $this->faker->creditCardNumber(),
            'date_of_birth' => $this->faker->unique()->dateTimeBetween('-50 years', '-20 years')->format('d-m-Y'),
            'email' => $this->faker->email(),
            'country' => $this->faker->country(),
            'phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->text(),
        ];
    }
}
