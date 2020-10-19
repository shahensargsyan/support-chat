<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
        ]);
    }
}
