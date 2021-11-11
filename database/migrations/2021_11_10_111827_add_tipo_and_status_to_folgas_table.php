<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoAndStatusToFolgasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folgas', function (Blueprint $table) {
            $table->string('tipo')->nullable()->after('dataaprovacao');
            $table->string('situacao')->nullable()->after('tipo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('folgas', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('situacao');
        });
    }
}
