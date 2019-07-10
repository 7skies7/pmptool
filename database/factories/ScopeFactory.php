<?php

use Faker\Generator as Faker;

$factory->define(App\Scope::class, function (Faker $faker) {
    return [
    	'crd_id' => 'CRD_1_'.factory('App\Project')->create()->id,
        'crd_title' => $faker->sentence(2),
        'crd_desc' => $faker->sentence(10),
        'crd_status' => $faker->numberBetween(1,3),
        'project_id' => factory('App\Project')->create()->id,
        'created_by' => factory('App\User')->create()->id,
        'modified_by' => factory('App\User')->create()->id,
    ];
});
