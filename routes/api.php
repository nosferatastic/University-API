<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\UserAccountController;
use \App\Http\Controllers\UserFavouriteController;
use \App\Http\Controllers\UniversityController;
use \App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('user', [UserAccountController::class, 'getUser'])->name('user.get');
Route::post('user/register', [UserAccountController::class, 'register'])->name('user.register');
Route::post('user/login', [UserAccountController::class, 'login'])->name('user.login');

Route::get('university/{university}', [UniversityController::class, 'getUniversity'])->name('university.get');
Route::get('search', [UniversityController::class, 'searchUniversities'])->name('university.search');

Route::put('university/{university}/review', [ReviewController::class, 'submitReview'])->name('review.submit');

Route::post('university/{university}/favourite', [UserFavouriteController::class, 'addFavourite'])->name('favourites.add');
Route::delete('university/{university}/favourite', [UserFavouriteController::class, 'removeFavourite'])->name('favourites.remove');