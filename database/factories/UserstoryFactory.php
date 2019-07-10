<?php

use Faker\Generator as Faker;

$factory->define(App\Userstory::class, function (Faker $faker) {
    return [
    	'userstory_id' => 'US_1_'.factory('App\Scope')->create()->id,
        'userstory_desc' => $faker->sentence(3),
        'project_id' => factory('App\Project')->create()->id,
        'cr_id' => factory('App\Scope')->create()->id,
        'userstory_point' => $faker->randomDigitNotNull,
        'userstory_status' => $faker->numberBetween(1,3),
        'userstory_priority' => $faker->numberBetween(1,3),
        'created_by' => factory('App\User')->create()->id,
        'modified_by' => factory('App\User')->create()->id,
    ];
});
