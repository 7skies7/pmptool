<?php

use Faker\Generator as Faker;

$factory->define(App\Program::class, function (Faker $faker) {
    return [
        'program_name' => $faker->sentence(1),
        'program_desc' => $faker->sentence(20),
        'program_start_date' => $faker->dateTime(),
        'program_end_date' => $faker->dateTime(),
        'program_manager' => factory('App\User')->create()->id,
        'created_by' => factory('App\User')->create()->id,
        'modified_by' => factory('App\User')->create()->id,
    ];
});
