<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        "post_id" => \App\Models\Post::query()->inRandomOrder()->first()->id,
        "user_id" => \App\Models\User::query()->inRandomOrder()->first()->id,
        "comment" => $faker->text,
    ];
});
