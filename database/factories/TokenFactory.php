<?php

use Faker\Generator as Faker;

$factory->define(\App\Mattermost\Token::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
    ];
});
