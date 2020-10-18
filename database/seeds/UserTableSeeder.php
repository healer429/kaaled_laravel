<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => "User1",
            'email' => "jeniln1@gmail.com",
            'password' => bcrypt('abc@123'),
            'logo' => "../../../assets/images/Ellipse211.png"
        ]);

        DB::table('users')->insert([
            'name' => "User2",
            'email' => "jeniln2@gmail.com",
            'password' => bcrypt('abc@123'),
            'logo' => "../../../assets/images/Ellipse197.png"
        ]);

        DB::table('users')->insert([
            'name' => "User3",
            'email' => "jeniln3@gmail.com",
            'password' => bcrypt('abc@123'),
            'logo' => "../../../assets/images/Ellipse207.png"
        ]);

    }
}
