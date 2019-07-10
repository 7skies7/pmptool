<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInitialStoryPointToUserstory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userstory', function (Blueprint $table) {
            $table->integer('initial_story_point')->after('userstory_point')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userstory', function (Blueprint $table) {
            $table->dropColumn('initial_story_point');
        });
    }
}
