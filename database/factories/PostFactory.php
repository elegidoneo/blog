<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        "title" => $faker->sentence,
        "body" => $faker->text,
        "image_url" => $faker->imageUrl(),
        "user_id" => \App\Models\User::query()->inRandomOrder()->first()->id,
    ];
});
