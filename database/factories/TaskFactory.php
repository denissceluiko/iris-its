<?php

use Faker\Generator as Faker;

$factory->define(\App\Task::class, function (Faker $faker) {
    return [
        'name' => $faker->words(rand(3, 6), true)
    ];
});
