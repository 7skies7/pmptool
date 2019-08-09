<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTaskAssigneeTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('task_assignee')->nullable()->change();
            $table->date('task_start_date')->nullable()->change();
            $table->date('task_end_date')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->integer('task_assignee')->change();
        $table->date('task_start_date')->change();
        $table->date('task_end_date')->change();
        
    }
}
