<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Bouncer;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Support\Facades\DB;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('abilities')->delete();
        DB::table('permissions')->delete();
        DB::table('assigned_roles')->delete();
        DB::table('roles')->delete();


        User::find(1)->assign('admin');
        User::find(2)->assign('user');

        Bouncer::allow('admin')->everything();

        Bouncer::allow('user')->toOwn(ClassModel::class);
        Bouncer::allow('user')->to('create', ClassModel::class);

    }
}
