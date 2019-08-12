<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProgramIdProgram extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manager_program', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->foreign('program_id')->references('id')->on('program')->onDelete('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // $table->foreign('program_id')->references('id')->on('program')->onDelete('cascade');
    }
}
