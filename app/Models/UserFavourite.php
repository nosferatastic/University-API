<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * UserFavourite link class. Holds data on which users have favourited which universities.
 */
class UserFavourite extends Model
{

    protected $connection = 'sqlite';
    protected $table = 'user_favourites';

    //This class has no timestamps
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'university_id'
    ];

    //For current functionality, this class does not need any functions or relations
}
