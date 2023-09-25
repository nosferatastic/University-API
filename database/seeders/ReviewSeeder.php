<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        $review = \App\Models\Review::create([
            'user_id' => 1,
            'user_name' => "Test User",
            'university_id' => 8,
            'review_comment' => "Loved it!",
            'rating' => 5,
        ]);
        $review->save();
        $review = \App\Models\Review::create([
            'user_id' => null,
            'user_name' => "Guest User",
            'university_id' => 8,
            'review_comment' => "It was okay.",
            'rating' => 2,
        ]);
        $review->save();
        $review = \App\Models\Review::create([
            'user_id' => null,
            'user_name' => "Guest User 2",
            'university_id' => 8,
            'review_comment' => "It was fine.",
            'rating' => 2,
        ]);
        $review->save();
        $review = \App\Models\Review::create([
            'user_id' => null,
            'user_name' => "Guest User 2",
            'university_id' => 14,
            'review_comment' => "It was pretty good.",
            'rating' => 4,
        ]);
        $review->save();
        $review = \App\Models\Review::create([
            'user_id' => null,
            'user_name' => "Guest User 3",
            'university_id' => 5,
            'review_comment' => "I had a lot of fun, but the teaching wasn't good at all.",
            'rating' => 2,
        ]);
        $review->save();
    }
}
