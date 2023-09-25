<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use \Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Validator;

use \App\Models\University;

/*
 * Class contains functions that allow retrieval of University objects
 */
class UniversityController extends BaseController
{

    /*
     * Retrieve a University profile.
     * 
     * @param University $university : from route binding. The university to retrieve
     */
    public function getUniversity(University $university): JsonResponse
    {
        if(!isset($university) || !$university->enabled) {
            return response()->json(['error' => "This university either does not exist or is private."], 404);
        }
        
        //Within return statement, getProfile() function called on University will retrieve/hide fields based on premium status
        return response()->json(['message' => 'Retrieved', 'university' => $university->getProfile()], 200);
    }

    /*
     * Perform a university search with the parameters in the request body.
     * 
     * @param Request $request : request object. Must contain search_term. Can contain sort_by, order
     */
    public function searchUniversities(Request $request): JsonResponse
    {
        //Get search term from request
        if(!$request->search_term) {
            return response()->json(['error' => "Invalid request. Please enter a search term."], 400);
        }
        //Get & validate sort criteria (either rating or name, and asc/desc)
        if(isset($request->sort_by) && !in_array($request->sort_by, ["name","rating"])) {
            return response()->json(['error' => "Invalid request. Please specify a valid sort."], 400);
        }
        if(isset($request->order) && !in_array($request->order, ["asc","desc"])) {
            return response()->json(['error' => "Invalid request. Please specify a valid sort order."], 400);
        }
        //Execute query with static function on University model. Default sort is name asc
        $results = University::search(
            $request->search_term, 
            $request->sort_by ?? "name", 
            $request->order ?? "asc"
        );
        //Return ID, name, logo path, reviews count, average rating
        return response()->json(['message' => 'Retrieved', 'results' => $results], 200);
    }
}
