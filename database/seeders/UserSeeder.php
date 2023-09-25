<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        $user = \App\Models\User::create([
            'name' => "Testing User",
            'email' => "testuser@testmail.com",
            'email_verified_at' => now(),
            'password' => bcrypt('Pass123!'), // password
            'remember_token' => Str::random(10),
        ]);
        $user->save();
    }
}
