<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

use \App\Models\University;
use \App\Models\Review;

/*
 * Class contains functions that allow submission of user Reviews
 */
class ReviewController extends BaseController
{

    /*
     * Request contains parameters for a user review. This function will submit
     * that review for the university matching the ID specified.
     * 
     * @param Request $request : request object. Contains rating, review_comment
     * @param University $university : from route binding. The university reviewed
     */
    public function submitReview(Request $request, University $university): JsonResponse
    {
        //Because we don't use the sanctum middleware here we must retrieve user
        $user = Auth::guard('sanctum')->user();
        //Check university exists and is enabled
        if(!$university || !$university->enabled) {
            return response()->json(['error' => "This university either does not exist or is private."], 404);
        }
        //If user is not logged in they must supply a user name
        if(!$user && !$request->user_name) {
            return response()->json(['error' => "Invalid request. Please log in or specify a name for this review."], 400);
        }
        //Validate review comment & rating (integer 1-5)
        if(!is_integer($request->rating) || $request->rating > 5 || $request->rating < 1) {
            return response()->json(['error' => "Invalid request. Please enter a valid rating."], 400);
        }

        //store
        $review = $this->storeReview([
            'user_id'           => $user ? $user->id : null,
            'user_name'         => $user ? $user->name : $request->user_name,
            'review_comment'    => $request->review_comment,
            'rating'            => $request->rating,
            'university_id'     => $university->id
        ]);

        return response()->json(['message' => "Review successfully submitted.", 'review' => $review], 201);

    }

    /*
     * Private function that takes the review data (validated prior) and
     * submits it.
     * 
     * @param array $reviewData : Array containing user ID/name, review_comment, rating, university_id
     * @return Review : Created review object from submitted data
     */
    private function storeReview($reviewData): Review
    {
        $review = Review::create($reviewData);
        $review->save();
        return $review;
    }
    
}
