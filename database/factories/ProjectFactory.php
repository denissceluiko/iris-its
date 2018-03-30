<?php

use Faker\Generator as Faker;

$factory->define(\App\Project::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'code' => $faker->currencyCode,
        'next_task_number' => 1,
    ];
});
