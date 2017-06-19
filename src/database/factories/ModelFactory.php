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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('test12'),
        'shopping_cart' => json_encode(array()),
        'cash' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 200000),
        'invest_score' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1000000),
        'remember_token' => str_random(10)
    ];
});
