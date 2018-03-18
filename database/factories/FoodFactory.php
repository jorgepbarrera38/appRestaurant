<?php

use Faker\Generator as Faker;

$factory->define(App\Food::class, function (Faker $faker) {
    return [
        'name' => $faker->text(50),
        'description' => $faker->text(150),
        'price' => $faker->numberBetween(5000, 30000),
    ];
});
