<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfissionalIdToMedicoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicoes', function (Blueprint $table) {
            $table->unsignedBigInteger('prestador_id')->after('ordemservico_id')->nullable();
            $table->foreign('prestador_id')->references('id')->on('prestadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicoes', function (Blueprint $table) {
            $table->dropForeign(['prestador_id']);
            $table->dropColumn('prestador_id');
        });
    }
}
