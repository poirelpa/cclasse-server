<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        // \App\Models\User::factory(10)->create();
        DB::table('users')->insert([[
            'Email' => 'paul.poirel@protonmail.com',
            'Name' => 'Administrator',
            'Password' => bcrypt('Azerty01')
        ],[
            'Email' => 'poirelpa@gmail.com',
            'Name' => 'Paul Poirel',
            'Password' => bcrypt('Azerty01')
        ]]);
    }
}
