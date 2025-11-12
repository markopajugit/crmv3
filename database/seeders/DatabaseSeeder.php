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
            //OrderServiceSeeder::class, // Uncomment to attach services to orders. Can also run separately: php artisan db:seed --class=OrderServiceSeeder
            SettingsSeeder::class,
            //CompanyOrderSeeder::class,
            //PersonOrderSeeder::class
        ]);
        $this->command->info('Tables seeded!');
    }
}
