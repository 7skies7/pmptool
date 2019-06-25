<?php

use Illuminate\Database\Seeder;

class ScopeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Scope', 10)->create();
    }
}
