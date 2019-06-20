<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
   return [
        'project_name' => $faker->sentence(2),
        'project_desc' => $faker->sentence(10),
        'project_start_date' => $faker->dateTime(),
        'project_end_date' => $faker->dateTime(),
        'project_status' => 1,
        'project_budget' => 10000,
        'created_by' => factory('App\User')->create()->id,
        'modified_by' => factory('App\User')->create()->id,
    ];
});
