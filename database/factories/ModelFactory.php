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

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->unique()->firstName,
        'last_name' => $faker->unique()->lastName,
        'email' => $faker->unique()->safeEmail,
        'gender' => $faker->randomElement(['Gender 1', 'Gender 2', 'Gender 3', 'Gender 4']),
        'last_ip' => $faker->ipv4
    ];
});
