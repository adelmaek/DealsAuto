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
        DB::table('acc_users')->insert([
            'username' => "user1",
            'fullname' => 'Adel Mahmoud',
            'password' => bcrypt('mypassword'),
            'mobNumber' => "01115260757",
        ]);
    }
}
