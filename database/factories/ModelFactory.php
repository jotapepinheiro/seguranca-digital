<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\{User, System, Controle};
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(System::class, function (Faker $faker) {
    return [
        'description' => $faker->unique()->company,
        'initial'     => strtoupper(substr($faker->unique()->jobTitle, 0, 10)),
        'email'     => $faker->unique()->safeEmail,
        'url'       => substr($faker->unique()->url, 0, 50),
        'status'    => $faker->randomElement(['active','canceled']),
        'created_by' => User::all()->random()->id
    ];
});

$factory->define(Controle::class, function (Faker $faker) {
    return [
        'system_id'    => System::all()->random()->id,
        'user_id'       => User::all()->random()->id,
        'justification' => $faker->text($maxNbChars = 200)
    ];
});
