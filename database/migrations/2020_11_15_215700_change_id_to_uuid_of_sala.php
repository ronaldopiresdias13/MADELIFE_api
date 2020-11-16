<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdToUuidOfSala extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::table('salas', function (Blueprint $table) {
        //     $table->uuid('id')->change();
        // });
        Schema::table('salas', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('salas', function (Blueprint $table) {
        //     $table->bigIncrements('id')->change();
        // });
        Schema::table('salas', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });
    }
}
