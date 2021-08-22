<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('levels')->delete();

        DB::table('levels')->insert([[
            'name' => 'PS',
            'order_index' => 3,
        ],[
            'name' => 'MS',
            'order_index' => 4,
        ],[
            'name' => 'GS',
            'order_index' => 5,
        ],[
            'name' => 'CP',
            'order_index' => 6,
        ],[
            'name' => 'CE1',
            'order_index' => 7,
        ],[
            'name' => 'CE2',
            'order_index' => 8,
        ],[
            'name' => 'CM1',
            'order_index' => 9,
        ],[
            'name' => 'CM2',
            'order_index' => 10,
        ]]);
    }
}
