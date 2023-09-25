<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

use \App\Models\University;
use \App\Models\UserFavourite;

/*
 * User account class. Holds user account info.
 * Functions within allow management of user favourites.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'sqlite';
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
     * Given a University, mark it as a favourite for the user by storing a UserFavourite object.
     * 
     * @param University $university : from route binding. The university to mark as favourite
     */
    public function storeFavouriteUniversity(University $university) : UserFavourite
    {
        $favourite = UserFavourite::create(['university_id' => $university->id, 'user_id' => $this->id]);
        return $favourite;
    }

    /*
     * Given a University, unmark it as a favourite for the user by deleting existing UserFavourite object(s).
     * 
     * @param University $university : from route binding. The university to unmark as favourite
     */
    public function removeFavouriteUniversity(University $university) : bool
    {
        //Delete any existing favourite objects matching this user & requested university
        $favourite = \App\Models\UserFavourite::where('university_id','=', $university->id)
                ->where('user_id','=', $this->id)->delete();
        return true;
    }

    /*
     * Retrieve this user's favourite universities. Used by University model to check favourite status.
     */
    public function getFavouriteUniversities(): Collection
    {
        return $this->favouriteUniversities;
    }
    
    //Relationships

    /*
     * Relation function definind link between User and their favourited University objects, through the UserFavourite model.
     */
    protected function favouriteUniversities(): hasManyThrough
    {
        return $this->hasManyThrough('App\Models\University','App\Models\UserFavourite', 
            'user_id', 'id', 
            'id', 'university_id');
    }
}
