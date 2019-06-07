<?php

use Faker\Generator as Faker;

$factory->define(App\Company::class, function (Faker $faker) {
    return [
        'company_name' => $faker->sentence(1),
        'company_desc' => $faker->sentence(20),
        'company_manager' => factory('App\User')->create()->id,
        'created_by' => 1,
        'modified_by' => 1

    ];
});
