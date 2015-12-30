<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Event::class, function (Faker\Generator $faker) {
    return [
        'title' => implode(' ', $faker->words()),
        'desc' => $faker->paragraph(),
        'lat' => $faker->randomFloat(0, 60),
        'lng' => $faker->randomFloat(0, 60),
        'creator_id' => rand(1, 20),
        'img'   => $faker->imageUrl(800, 224),
    ];
});
