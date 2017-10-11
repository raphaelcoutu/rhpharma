<?php

use Faker\Generator as Faker;

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
$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'workdays_per_week' => 5,
        'seniority' => 0,
        'is_active' => 1,
        'branch_id' => 1
    ];
});

$factory->define(App\ConstraintType::class, function(Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'code' => $faker->firstName,
        'is_work' => $faker->boolean(30),
        'is_single_day' => $faker->boolean(50),
        'is_group_constraint' => 0,
        'is_day_in_schedule' => 0,
        'branch_id' => 1
    ];
});
