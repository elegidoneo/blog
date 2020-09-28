<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Post;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * @param RatingRequest $request
     * @param Post $post
     * @param Rating $rating
     * @return \Illuminate\Http\JsonResponse
     */
    public function qualify(RatingRequest $request, Post $post, Rating $rating)
    {
        $request->user()->rate($post, $request->input('qualify'));
        $response = $rating->query()->first();
        return response()->json([
            "rating" => $response->rateable,
            "qualifier" => $response->qualifier
        ]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function averageRating(Request $request, Post $post)
    {
        return response()->json([
            "post" => $post->id,
            "average" => $post->averageRating(get_class($request->user())),
        ]);
    }
}
