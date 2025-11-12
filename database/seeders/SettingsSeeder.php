<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'key' => 'Company Name',
            'value' => 'Sandis CRM',
        ]);

        DB::table('settings')->insert([
            'key' => 'Next_Invoice_no',
            'value' => '1',
        ]);
    }
}
