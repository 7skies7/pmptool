<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserstoryIdToScopeComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scope_comments', function (Blueprint $table) {
            $table->unsignedInteger('userstory_id')->after('crd_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scope_comments', function (Blueprint $table) {
            $table->dropColumn('userstory_id');
        });
    }
}
