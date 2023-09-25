<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

use \App\Models\University;

/*
 * Class contains functions that allow management of user favourites (favourited universities)
 */
class UserFavouriteController extends BaseController
{

    /*
     * For the logged in user, register the given university as a favourite.
     * 
     * @param University $university : from route binding. The university to mark as favourite
     */
    public function addFavourite(University $university): JsonResponse
    {
        //Check authenticated
        $user = Auth::guard('sanctum')->user();
        if(!$user) {
            return response()->json(['error' => 'Not authorised.'], 401);
        }
        //Check university exists and is enabled
        if(!$university || !$university->enabled) {
            return response()->json(['error' => "This university either does not exist or is private."], 404);
        }
        //If already favourite return success already favourite
        if($university->isUserFavourite()) {
            return response()->json(['message' => 'Already a favourite.'], 200);
        }
        //Store favourite
        $user->storeFavouriteUniversity($university);
        return response()->json(['message' => 'Stored as favourite.'], 201);
    }

    /*
     * For the logged in user, remove the given university as a favourite.
     * 
     * @param University $university : from route binding. The university to remove as favourite
     */
    public function removeFavourite(University $university): JsonResponse
    {
        //Check authenticated
        $user = Auth::guard('sanctum')->user();
        if(!$user) {
            return response()->json(['error' => 'Not authorised.'], 401);
        }
        //Check university exists and is enabled
        if(!$university || !$university->enabled) {
            return response()->json(['error' => "This university either does not exist or is private."], 404);
        }
        //If already not favourite return success already not favourite
        if(!$university->isUserFavourite()) {
            return response()->json(['message' => 'Not a favourite.'], 200);
        }
        //Remove any relevant favourites
        $user->removeFavouriteUniversity($university);
        return response()->json(['message' => 'Favourite removed.'], 201);
    }
}
