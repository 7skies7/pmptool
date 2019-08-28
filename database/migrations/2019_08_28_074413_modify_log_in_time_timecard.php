<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyLogInTimeTimecard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timecard', function (Blueprint $table) {
            $table->time('log_in_time')->nullable()->change();
            $table->time('log_out_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timecard', function (Blueprint $table) {
            $table->datetime('log_in_time')->change();
            $table->datetime('log_out_time')->change();
        });
    }
}
