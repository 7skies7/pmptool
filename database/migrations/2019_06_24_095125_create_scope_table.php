<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScopeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scope', function (Blueprint $table) {
            $table->increments('id');
            $table->string('crd_id')->unique();
            $table->unsignedInteger('project_id');
            $table->string('crd_title');
            $table->text('crd_desc');
            $table->integer('crd_status');
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scope');
    }
}
