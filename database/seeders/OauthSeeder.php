<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->delete();

        $datetime = Carbon::now();

        $clients = [
            [
                'id' => 1,
                'secret' => 'Gl4z1uob1D1spvWZ7llFTh5orQUSF5HvzN9LunKO',
                'name' => 'CClasse web SPA client',
                'provider' => 'users',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ]
        ];

        DB::table('oauth_clients')->insert($clients);

    }
}
