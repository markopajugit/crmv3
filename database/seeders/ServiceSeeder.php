<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'name' => 'Law',
            'cost' => '0',
            'type' => 'regular',
        ]);

        Service::create([
            'name' => 'IT',
            'cost' => '0',
            'type' => 'regular',
        ]);

        Service::create([
            'name' => 'Consultation',
            'cost' => '0',
            'type' => 'regular',
        ]);
    }
}
