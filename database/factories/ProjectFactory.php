<?php

use Faker\Generator as Faker;

$factory->define(\App\Project::class, function (Faker $faker) {
    return [
        'name' => str_replace('.', '', $faker->name),
        'code' => $faker->currencyCode,
    ];
});
