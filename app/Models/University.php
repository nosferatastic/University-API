<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Auth;

/*
 * University Class - Represents a University profile.
 * Holds name, description, phone number, address, logo image path, website, and enabled/premium settings.
 * Functions extend to allow for search, and for retrieval of review info and user favourite status.
 */
class University extends Model
{
    protected $connection = 'sqlite';
    protected $table = 'universities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'phone_number',
        'address',
        'logo_image_path',
        'website',
        'enabled',
        'is_premium'
    ];

    /*
     * Perform a search of University objects with the given parameters, returned in the required format.
     * 
     * @param string $search_term : string query term to return University objects whose name contains this term.
     * @param string $sort_by : string term indicating on what to order reviews. Must be "name" or "rating".
     * @param string $order : string query term indicating how to order reviews. Must be "asc" or "desc".
     */
    public static function search($search_term, $sort_by = "name", $order = "asc"): Collection
    {
        $user = Auth::guard('sanctum')->user();
        return University::select(
            'universities.id','universities.name','universities.logo_image_path as logo_path',
            \DB::raw('count(distinct reviews.id) as reviews_count'),
            \DB::raw('avg(reviews.rating) as rating'),
            \DB::raw('case when user_favourites.university_id is null then 0 else 1 end as saved_as_favourite')
        )
        //Link to reviews and user favourites to retrieve these
        ->leftJoin('reviews','reviews.university_id','=','universities.id')
        ->leftJoin('user_favourites', function($j) use ($user) {
            $j->on('user_favourites.university_id','=','universities.id')
              ->where('user_favourites.user_id','=', $user?->id);
        })
        ->where('name','like','%'.$search_term.'%')
        ->where('universities.enabled','=','1')
        ->groupBy('universities.id')->orderBy($sort_by, $order)->get();
    }

    /*
     * Returns the "University Profile" array for this University, to be returned in the GET University API call. 
     * Returns different fields depending on the Premium status.
     */
    public function getProfile(): array
    {
        //Retrieve average rating and user favourite status (we can show rating on basic profiles as it appears anyway on search)
        $this->rating = $this->getAverageRating();
        $this->saved_as_favourite = (int) $this->isUserFavourite();

        //If it is premium, we retrieve & return additional fields vs a basic profile.
        if($this->is_premium) {
            //Add additional field to return info on reviews
            $this->list_of_reviews = $this->getReviews();
            return $this->only(['id','name','description','phone_number','address','logo_image_path','website','list_of_reviews', 'saved_as_favourite']);
        } else {
            //Basic profile has limited return values
            return $this->only('id','name','logo_image_path','rating','saved_as_favourite');
        }
    }

    /*
     * Return a count of the number of reviews submitted/stored for this University.
     */
    public function getReviewsCount(): int 
    {
        return $this->reviews()->count();
    }

    /*
     * Return a Collection of all of the reviews for this University.
     */
    public function getReviews(): Collection 
    {
        return $this->reviews()
                    ->select('id','user_name','review_comment',
                        'rating','modified_date')
                    ->get();
    }

    /*
     * Return a float value, the average rating (1-5) for this University across all its reviews.
     * Will return null if there are 0 reviews.
     */
    public function getAverageRating(): float|null 
    {
        return $this->reviews()->average('rating');
    }

    /*
     * Return a boolean indicating if the current User has marked this University as favourite.
     * Returns false if there is no User logged in.
     */
    public function isUserFavourite(): bool
    {
        $user = Auth::guard('sanctum')->user();
        if(!$user) {
            return false;
        }
        return $user->getFavouriteUniversities()->contains($this);
    }


    //Relationships

    /*
     * Relation defining link between University and Reviews for that University.
     */
    protected function reviews(): hasMany
    {
        return $this->hasMany('\App\Models\Review','university_id','id');
    }


}
