<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use DB;

class UserFavouriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        $userFav = \App\Models\UserFavourite::create([
            'user_id' => 1,
            'university_id' => 8
        ]);
        $userFav->save();
    }
}
