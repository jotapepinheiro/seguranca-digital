<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{User, Sistema, Controle};
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

$factory->define(Sistema::class, function (Faker $faker) {
    return [
        'descricao' => $faker->unique()->company,
        'sigla'     => strtoupper(substr($faker->unique()->jobTitle, 0, 10)),
        'email'     => $faker->unique()->safeEmail,
        'url'       => substr($faker->unique()->url, 0, 50),
        'status'    => $faker->randomElement(['ativo','cancelado']),
        'created_by' => User::all()->random()->id
    ];
});

$factory->define(Controle::class, function (Faker $faker) {
    return [
        'sistema_id'    => Sistema::all()->random()->id,
        'user_id'       => User::all()->random()->id,
        'justificativa' => $faker->text($maxNbChars = 200)
    ];
});
