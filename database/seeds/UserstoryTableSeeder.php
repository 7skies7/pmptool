<?php

use Illuminate\Database\Seeder;

class UserstoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Userstory', 10)->create();
    }
}
