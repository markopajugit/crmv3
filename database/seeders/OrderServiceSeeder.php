<?php

namespace Database\Seeders;

use App\Models\OrderService;
use Illuminate\Database\Seeder;

class OrderServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderService::factory()->count(100)->create();
    }
}
