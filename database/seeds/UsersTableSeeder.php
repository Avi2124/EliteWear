<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=array(
            array(
                'name'=>'CodeAstro',
                'email'=>'admin@mail.com',
                'password'=>Hash::make('codeastro.com'),
                'role'=>'admin',
                'status'=>'active'

                // 'name'=>'Avi Italiya',
                // 'email'=>'avi@gmail.com',
                // 'password'=>Hash::make('123456789'),
                // 'role'=>'admin',
                // 'status'=>'active'
            ),
            array(
                'name'=>'Customer A',
                'email'=>'customer@mail.com',
                'password'=>Hash::make('codeastro.com'),
                'role'=>'user',
                'status'=>'active'

                // 'name'=>'Customer',
                // 'email'=>'customer@gmail.com',
                // 'password'=>Hash::make('123456789'),
                // 'role'=>'user',
                // 'status'=>'active'
            ),
        );

        DB::table('users')->insert($data);
    }
}
