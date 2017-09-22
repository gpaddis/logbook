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
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/**
 * Counters
 */
$factory->define(App\PatronCategory::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->colorName,
        'abbreviation' => $faker->unique()->word,
    ];
});

$factory->define(App\RequestCategory::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->colorName,
        'abbreviation' => $faker->unique()->word,
    ];
});

$factory->define(App\Logbook\Entry::class, function (Faker\Generator $faker) {
    return [
        'start_time' => \Timeslot\Timeslot::now()->start(),
        'end_time' => \Timeslot\Timeslot::now()->end(),
        'patron_category_id' => function () {
            return factory('App\PatronCategory')->create()->id;
        },
        'visits' => $faker->randomDigitNotNull,
    ];
});

$factory->define(App\LogbookEntry::class, function (Faker\Generator $faker) {
    return [
        'patron_category_id' => function () {
            return factory('App\PatronCategory')->create()->id;
        },
        'visited_at' => \Timeslot\Timeslot::now()->start(),
        'recorded_live' => false
    ];
});
