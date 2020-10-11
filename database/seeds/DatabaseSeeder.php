<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::table('acc_users')->insert([
            'username' => "admin",
            'fullname' => 'Admin',
            'password' => bcrypt('12341234'),
            'mobNumber' => "012",
        ]);
        
        DB::table('acc_users')->insert([
            'username' => "wessam",
            'fullname' => 'Wessam El Masry',
            'password' => bcrypt('0000'),
            'mobNumber' => "012",
        ]);
    }
}
