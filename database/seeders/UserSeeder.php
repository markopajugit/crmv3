<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Marko',
            'email' => 'marko@hardcoded.ee',
            'password' => Hash::make('blasonsimlen'),
        ]);

        DB::table('users')->insert([
            'name' => 'Merle',
            'email' => 'merle@hardcoded.ee',
            'password' => Hash::make('blasonsimlen'),
        ]);

        DB::table('users')->insert([
            'name' => 'Kristine Akopdžanjan',
            'email' => 'kristine@wisorgroup.com',
            'password' => '$2y$10$eAAF5MD2VhUzDt16OYrQAOKLVyWIg935t4iaoRAjKl0z6ZSdC14Tq',
        ]);

        DB::table('users')->insert([
            'name' => 'Armine Kocharyan',
            'email' => 'armine@wisorgroup.com',
            'password' => '$2y$10$ltekzixs/ekWfJbZg1q8leHgIIYME4K9w2KEVCHKhhrNm70sd08De',
        ]);
    }
}
