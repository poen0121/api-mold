<?php

use Faker\Generator as Faker;

$factory->define(App\Entities\Service\Client::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});