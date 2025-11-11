<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            OrderSeeder::class,
            PersonSeeder::class,
            PersonCompanySeeder::class,
            InvoiceSeeder::class,
            ServiceSeeder::class,
            //OrderServiceSeeder::class,
            SettingsSeeder::class,
            //CompanyOrderSeeder::class,
            //PersonOrderSeeder::class,
            NoteSeeder::class,
            PaymentSeeder::class,
            EntityContactSeeder::class,
            EntityAddressSeeder::class,
            EntityRiskSeeder::class,
        ]);
        $this->command->info('Tables seeded!');
    }
}
