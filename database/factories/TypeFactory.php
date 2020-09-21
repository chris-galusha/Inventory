<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Type;
use Faker\Generator as Faker;

$factory->define(Type::class, function (Faker $faker) {
    return [
        'html_type' => $faker->word,
        'sql_type' => $faker->word,
        'display_name' => $faker->word,
        'protected' => $faker->boolean,
        'name' => $faker->word
    ];
});
