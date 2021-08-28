<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('academies')->delete();
        DB::table('holiday_zones')->delete();

        DB::table('holiday_zones')->insert([[
            'name' => 'alsace-moselle'
        ],[
              'name' => 'guadeloupe'
        ],[
              'name' => 'guyane'
        ],[
              'name' => 'la-reunion'
        ],[
              'name' => 'martinique'
        ],[
              'name' => 'mayotte'
        ],[
              'name' => 'metropole'
        ],[
              'name' => 'nouvelle-caledonie'
        ],[
              'name' => 'polynesie-francaise'
        ],[
              'name' => 'saint-barthelemy'
        ],[
              'name' => 'saint-martin'
        ],[
              'name' => 'saint-pierre-et-miquelon'
        ],[
              'name' => 'wallis-et-futuna'
        ]]);


        DB::table('academies')->insert([[
            "name" => 'Aix-Marseille',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Amiens',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Besançon',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Bordeaux',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Clermont-Ferrand',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Créteil',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Dijon',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Grenoble',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Lille',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Limoges',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Lyon',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Montpellier',
            "holiday_zone_id" => 1
        ],[
            "name" => 'Nancy-Metz',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Nantes',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Nice',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Normandie',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Nouvelle Calédonie',
            "holiday_zone_id" => 8
        ],[
            "name" => 'Orléans-Tours',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Paris',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Poitiers',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Polynésie',
            "holiday_zone_id" => 9
        ],[
            "name" => 'Reims',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Rennes',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Strasbourg',
            "holiday_zone_id" => 1
        ],[
            "name" => 'Toulouse',
            "holiday_zone_id" => 7
        ],[
            "name" => 'Versailles',
            "holiday_zone_id" => 7
        ]]);
    }
}
