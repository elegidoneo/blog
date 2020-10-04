<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Rating::class, function (Faker $faker) {
    return [
        "score" => $faker->numberBetween(1, 5),
        "rateable_type" =>  \App\Models\Post::class,
        "rateable_id" => \App\Models\Post::query()->inRandomOrder()->first()->id,
        "qualifier_type" => \App\Models\User::class,
        "qualifier_id" => \App\Models\User::query()->inRandomOrder()->first()->id
    ];
});
