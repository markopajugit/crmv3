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
        $users = [
            [
                'name' => 'Marko',
                'email' => 'marko@hardcoded.ee',
                'password' => Hash::make('blasonsimlen'),
            ],
            [
                'name' => 'Merle',
                'email' => 'merle@hardcoded.ee',
                'password' => Hash::make('blasonsimlen'),
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
