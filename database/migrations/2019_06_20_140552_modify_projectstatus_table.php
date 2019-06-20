<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProjectstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project', function (Blueprint $table) {
            $table->integer('project_status')->change();
            $table->integer('project_budget')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('project_status')->change();
            $table->unsignedInteger('project_budget')->change();
        });
    }
}
