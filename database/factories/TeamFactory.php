<?php

use Faker\Generator as Faker;

$factory->define(\App\Team::class, function (Faker $faker) {
    return [
        'mm_id' => $faker->uuid,
        'mm_domain' => $faker->words(2, true),
    ];
});
