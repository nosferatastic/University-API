<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * Review Class - Represents a User Review of a University.
 * Holds user/university IDs, user name, review comment, and rating.
 */
class Review extends Model
{
    protected $connection = 'sqlite';
    protected $table = 'reviews';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'university_id',
        'user_name',
        'review_comment',
        'rating',
        'modified_date'
    ];

    //Below ensures that, if the object is updated, the database modified_date will automatically be updated to current datetime
    protected $dates = [
        'modified_date'
    ];    
    const UPDATED_AT = 'modified_date';

    //For current functionality, this class does not need any functions or relations
}
