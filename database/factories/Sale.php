<?php

use Faker\Generator as Faker;

$factory->define(App\Sale::class, function (Faker $faker) {
    return [
        'table_id' => \App\Table::all()->random()->id,
        'pricetotal' => $faker->numberBetween(5000, 40000)
    ];
});
