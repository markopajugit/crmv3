<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Note;
use App\Models\Order;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $companies = Company::all();
        $persons = Person::all();
        $orders = Order::all();

        if ($users->isEmpty() || ($companies->isEmpty() && $persons->isEmpty() && $orders->isEmpty())) {
            $this->command->warn('Skipping NoteSeeder: No users or entities found.');
            return;
        }

        // Create notes for companies
        if ($companies->isNotEmpty()) {
            foreach ($companies->random(min(30, $companies->count())) as $company) {
                try {
                    Note::create([
                        'company_id' => $company->id,
                        'person_id' => null,
                        'order_id' => null,
                        'content' => fake()->sentence(rand(10, 30)),
                        'user_id' => $users->random()->id,
                        'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                        'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
                    ]);
                } catch (\Exception $e) {
                    // Skip on any constraint errors
                    continue;
                }
            }
        }

        // Create notes for persons
        if ($persons->isNotEmpty()) {
            foreach ($persons->random(min(30, $persons->count())) as $person) {
                try {
                    Note::create([
                        'company_id' => null,
                        'person_id' => $person->id,
                        'order_id' => null,
                        'content' => fake()->sentence(rand(10, 30)),
                        'user_id' => $users->random()->id,
                        'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                        'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
                    ]);
                } catch (\Exception $e) {
                    // Skip on any constraint errors
                    continue;
                }
            }
        }

        // Create notes for orders
        if ($orders->isNotEmpty()) {
            foreach ($orders->random(min(40, $orders->count())) as $order) {
                try {
                    Note::create([
                        'company_id' => null,
                        'person_id' => null,
                        'order_id' => $order->id,
                        'content' => fake()->sentence(rand(10, 30)),
                        'user_id' => $users->random()->id,
                        'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                        'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
                    ]);
                } catch (\Exception $e) {
                    // Skip on any constraint errors
                    continue;
                }
            }
        }
    }
}

