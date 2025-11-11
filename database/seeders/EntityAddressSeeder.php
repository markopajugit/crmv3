<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\EntityAddress;
use App\Models\Person;
use Illuminate\Database\Seeder;

class EntityAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::all();
        $persons = Person::all();

        if ($companies->isEmpty() && $persons->isEmpty()) {
            $this->command->warn('Skipping EntityAddressSeeder: No companies or persons found.');
            return;
        }

        // Create addresses for companies
        if ($companies->isNotEmpty()) {
            foreach ($companies->random(min(35, $companies->count())) as $company) {
                $addressCount = rand(1, 2);
                
                for ($i = 0; $i < $addressCount; $i++) {
                    try {
                        EntityAddress::create([
                            'addressable_type' => Company::class,
                            'addressable_id' => $company->id,
                            'type' => fake()->randomElement(['billing', 'shipping', 'registered', 'operational']),
                            'street' => fake()->streetAddress(),
                            'city' => fake()->city(),
                            'zip' => fake()->postcode(),
                            'country' => fake()->country(),
                            'notes' => fake()->optional(0.3)->sentence(rand(5, 15)),
                            'is_primary' => $i === 0,
                            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
                            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
                        ]);
                    } catch (\Exception $e) {
                        // Skip on any constraint errors
                        continue;
                    }
                }
            }
        }

        // Create addresses for persons
        if ($persons->isNotEmpty()) {
            foreach ($persons->random(min(35, $persons->count())) as $person) {
                $addressCount = rand(1, 2);
                
                for ($i = 0; $i < $addressCount; $i++) {
                    try {
                        EntityAddress::create([
                            'addressable_type' => Person::class,
                            'addressable_id' => $person->id,
                            'type' => fake()->randomElement(['residential', 'billing', 'mailing']),
                            'street' => fake()->streetAddress(),
                            'city' => fake()->city(),
                            'zip' => fake()->postcode(),
                            'country' => fake()->country(),
                            'notes' => fake()->optional(0.2)->sentence(rand(5, 12)),
                            'is_primary' => $i === 0,
                            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
                            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
                        ]);
                    } catch (\Exception $e) {
                        // Skip on any constraint errors
                        continue;
                    }
                }
            }
        }
    }
}

