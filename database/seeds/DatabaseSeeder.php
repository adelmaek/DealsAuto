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
        // $this->call(UserSeeder::class);
        // DB::table('acc_users')->insert([
        //     'username' => "user1",
        //     'fullname' => 'Adel Mahmoud',
        //     'password' => bcrypt('mypassword'),
        //     'mobNumber' => "01115260757",
        // ]);
        DB::table('acc_users')->insert([
            'username' => "wessam",
            'fullname' => 'Wessam El Masry',
            'password' => bcrypt('0000'),
            'mobNumber' => "01009831632",
        ]);
    }
}
