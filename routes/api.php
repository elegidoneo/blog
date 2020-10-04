<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/login", "Auth\LoginController@login");
Route::post("/user", "UserController@store");
Route::get("/post", "PostController@index");
Route::middleware('auth:api')->group(function () {
    Route::apiResource("/user", "UserController")->except("store");
    Route::apiResource("/post", "PostController")->except('index');
    Route::apiResource("/comment", "CommentController");
    Route::post("/qualify/{post}", "RatingController@qualify");
    Route::get("/average-rating/{post}", "RatingController@averageRating");
    Route::get("/logout", "Auth\LoginController@logout");
});
