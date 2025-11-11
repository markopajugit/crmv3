<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            'key' => 'Company Name',
            'value' => 'CRM',
        ]);

        Settings::create([
            'key' => 'Next_Invoice_no',
            'value' => '1',
        ]);
    }
}
