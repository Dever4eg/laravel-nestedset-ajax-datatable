<?php

use Faker\Generator as Faker;

$factory->define(App\Employee::class, function (Faker $faker) {
    return [
        'fullname'  => $faker->name,
        'position'  => $faker->jobTitle,
        'date'      => $faker->date,
        'salary'    => $faker->numberBetween(1000, 20000),
        'avatar'    => 'default.jpg',
    ];
});
