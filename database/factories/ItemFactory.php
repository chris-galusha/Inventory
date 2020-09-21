<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Log;

$factory->define(Item::class, function (Faker $faker) {

  $inventory_number = $faker->numberBetween($min = 1, $max = 1000000);
  while (Item::where('inventory_number', $inventory_number)->exists()) {
    $inventory_number = $faker->numberBetween($min = 1, $max = 1000000);
  }
  $serial_number = $faker->numberBetween($min = 1, $max = 1000000);
  while (Item::where('serial_number', $inventory_number)->exists()) {
    $serial_number = $faker->numberBetween($min = 1, $max = 1000000);
  }
  $random = rand(1, 10);
  $deleted_at = null;

  if ($random == 1) {
    $deleted_at = $faker->dateTime;
  }

    return [
        'model_number' => $faker->numberBetween($min = 1, $max = 10000),
        'description' => $faker->word,
        'manufacturer' => $faker->word,
        'serial_number' => $serial_number,
        'inventory_number' => $inventory_number,
        'owner' => $faker->name,
        'department' => $faker->word,
        'location' => $faker->word,
        'date_acquired' => $faker->date,
        'date_manufactured' => $faker->date,
        'status' => $faker->word,
        'date_decommissioned' => $faker->date,
        'last_inventoried' => $faker->date,
        'notes' => $faker->sentence,
        'deleted_at' => $deleted_at
    ];
});
