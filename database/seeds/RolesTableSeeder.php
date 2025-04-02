<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['role' => 'admin'],
            ['role' => 'user'],
            ['role' => 'editor'],
        ]);
    }
}

