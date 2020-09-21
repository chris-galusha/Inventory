<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Column;
use Faker\Generator as Faker;

$factory->define(Column::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'display_name' => $faker->word,
        'type_id' => function () {
          return factory(App\Type::class)->create()->id;
        },
        'display' => $faker->boolean,
        'protected' => $faker->boolean,
        'required' => $faker->boolean
    ];
});
