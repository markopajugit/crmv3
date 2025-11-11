<?php

namespace Database\Seeders;

use App\Models\CompanyOrder;
use Illuminate\Database\Seeder;

class CompanyOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyOrder::factory()->count(100)->create();
    }
}
