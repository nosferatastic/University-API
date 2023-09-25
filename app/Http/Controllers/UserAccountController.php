<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

use Validator;

/*
 * Class contains functions that allow login/registration of user accounts
 */
class UserAccountController extends BaseController
{

    /*
     * Register a user and return their auth token.
     * 
     * @param Request $request : request object. Must contain email, name, password matching desired formats
     */
    public function register(Request $request): JsonResponse
    {
        //User must name a name, email, password
        $validated = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
        
        if($validated->fails()) {
            //Handle failed registration
            return response()->json(['error' => "Invalid registration details provided."], 400);
        }

        //Create & Store User
        $user = \App\Models\User::create($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        //Return response with User ID & Token
        $success = [
            'message' => "User account created.",
            'user_id' => $user->id,
            'user' => $user,
            'token' => $user->createToken('unicompare')->plainTextToken
        ];
        return response()->json($success, 201);
    }


    /*
     * Log in for an existing user and return their auth token.
     * 
     * @param Request $request : request object. Must contain email, password
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            //Return response with User ID & Token
            $success = [
                'message' => "Logged in successfully.",
                'user_id' => $user->id,
                'token' => $user->createToken('unicompare')->plainTextToken
            ];
            return response()->json($success, 201);
        } else {
            //Handle failed registration
            return response()->json(['error' => "Invalid login details."], 401);
        }
    }

    /*
     * Endpoint to retrieve logged in user if logged in
     */
    public function getUser(): JsonResponse
    {
        if(Auth::guard('sanctum')->user()) {
            return response()->json(['user' => Auth::guard('sanctum')->user()], 200);
        }
        return response()->json(['error' => 'Unauthorised'], 401);
    }
}
