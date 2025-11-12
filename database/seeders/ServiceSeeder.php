<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'name' => 'Law stuff',
            'cost' => '0',
            'type' => 'regular',
        ]);

        DB::table('services')->insert([
            'name' => 'IT',
            'cost' => '0',
            'type' => 'regular',
        ]);

        DB::table('services')->insert([
            'name' => 'Consultation',
            'cost' => '0',
            'type' => 'regular',
        ]);
    }
}
