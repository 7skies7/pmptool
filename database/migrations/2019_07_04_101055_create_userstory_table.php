<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserstoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userstory', function (Blueprint $table) {
            $table->increments('id');
            $table->string('userstory_id')->unique();
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('cr_id');
            $table->text('userstory_desc');
            $table->integer('userstory_point');
            $table->integer('userstory_status');
            $table->integer('userstory_priority');
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userstory');
    }
}
