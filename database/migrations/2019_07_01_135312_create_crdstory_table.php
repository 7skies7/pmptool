<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrdstoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crdstory', function (Blueprint $table) {
            $table->increments('id');
            $table->text('user_story');
            $table->integer('story_points');
            $table->unsignedInteger('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crdstory');
    }
}
