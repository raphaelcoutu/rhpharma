<?php

use Illuminate\Support\Str;
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

$factory->define(App\Constraint::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'start_datetime' => \Carbon\Carbon::parse('-2 weeks'),
        'end_datetime' => \Carbon\Carbon::parse('-2 weeks'),
        'constraint_type_id' => 1,
        'weight' => 0,
        'comment' => '',
        'status' => 1,
        'validated_by' => null,
        'number_of_occurrences' => null,
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

$factory->define(App\Department::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => $faker->countryCode,
        'description' => '...',
        'branch_id' => '1',
        'workplace_id' => '1',
        'bonus_weeks' => 2,
        'bonus_pts' => 4,
        'malus_weeks' => 3,
        'malus_pts' => 8,
        'monday_am' => 2,
        'monday_pm' => 2,
        'tuesday_am' => 2,
        'tuesday_pm' => 2,
        'wednesday_am' => 2,
        'wednesday_pm' => 2,
        'thursday_am' => 2,
        'thursday_pm' => 2,
        'friday_am' => 2,
        'friday_pm' => 2
    ];
});

$factory->define(App\AssignedShift::class, function (Faker $faker) {
    return [
        'shift_type_id' => 1,
        'is_generated' => 1,
        'is_published' => 0,
    ];
});

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => Str::random(10),
        'workdays_per_week' => 5,
        'seniority' => 0,
        'is_active' => 1,
        'branch_id' => 1
    ];
});



