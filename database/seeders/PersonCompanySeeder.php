<?php

namespace Database\Seeders;

use App\Models\PersonCompany;
use Illuminate\Database\Seeder;

class PersonCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PersonCompany::factory()->count(100)->create();
    }
}
