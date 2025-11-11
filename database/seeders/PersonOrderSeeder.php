<?php

namespace Database\Seeders;

use App\Models\OrderPerson;
use Illuminate\Database\Seeder;

class PersonOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderPerson::factory()->count(100)->create();
    }
}
