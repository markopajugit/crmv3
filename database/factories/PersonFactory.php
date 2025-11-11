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
            'address' => $this->faker->address(),
            'id_code' => $this->faker->creditCardNumber() . '_' . uniqid(),
            'date_of_birth' => $this->faker->dateTimeBetween('-50 years', '-20 years')->format('d-m-Y'),
            'email' => $this->faker->email() . '_' . uniqid(),
            'country' => $this->faker->country(),
            'phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->text(),
        ];
    }
}
