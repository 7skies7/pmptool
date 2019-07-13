<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_heirarchy', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_level');
            $table->string('level_initial');
        });

        Schema::create('task_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
        });
        
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_id')->unique();
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('cr_id');
            $table->unsignedInteger('userstory_id');
            $table->unsignedInteger('task_heirarchy');
            $table->unsignedInteger('task_type');
            $table->integer('parent_id')->nullable();
            $table->text('task_desc');
            $table->integer('task_priority');
            $table->integer('task_assignee');
            $table->float('task_point', 8, 2);
            $table->integer('task_completion');
            $table->date('task_start_date');
            $table->date('task_end_date');
            $table->date('task_completion_date');
            $table->integer('is_deleted')->default(0);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('modified_by');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('project')->onDelete('cascade');
            $table->foreign('cr_id')->references('id')->on('scope')->onDelete('cascade');
            $table->foreign('task_heirarchy')->references('id')->on('task_heirarchy')->onDelete('cascade');
            $table->foreign('task_type')->references('id')->on('task_type')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('tasks');
    }
}
