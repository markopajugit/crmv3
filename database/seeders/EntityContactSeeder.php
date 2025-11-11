<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\EntityContact;
use App\Models\Person;
use Illuminate\Database\Seeder;

class EntityContactSeeder extends Seeder
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
            $this->command->warn('Skipping EntityContactSeeder: No companies or persons found.');
            return;
        }

        // Create contacts for companies
        if ($companies->isNotEmpty()) {
            foreach ($companies->random(min(40, $companies->count())) as $company) {
                $contactCount = rand(1, 3);
                
                for ($i = 0; $i < $contactCount; $i++) {
                    try {
                        $type = fake()->randomElement(['email', 'phone', 'mobile', 'fax']);
                        $value = $type === 'email' 
                            ? fake()->email() . '_' . uniqid()
                            : ($type === 'fax' 
                                ? fake()->phoneNumber() . '_' . uniqid()
                                : fake()->phoneNumber() . '_' . uniqid());
                        
                        EntityContact::create([
                            'contactable_type' => Company::class,
                            'contactable_id' => $company->id,
                            'type' => $type,
                            'value' => $value,
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

        // Create contacts for persons
        if ($persons->isNotEmpty()) {
            foreach ($persons->random(min(40, $persons->count())) as $person) {
                $contactCount = rand(1, 2);
                
                for ($i = 0; $i < $contactCount; $i++) {
                    try {
                        $type = fake()->randomElement(['email', 'phone', 'mobile']);
                        $value = $type === 'email' 
                            ? fake()->email() . '_' . uniqid()
                            : fake()->phoneNumber() . '_' . uniqid();
                        
                        EntityContact::create([
                            'contactable_type' => Person::class,
                            'contactable_id' => $person->id,
                            'type' => $type,
                            'value' => $value,
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

