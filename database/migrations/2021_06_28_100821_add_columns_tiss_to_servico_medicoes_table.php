<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsTissToServicoMedicoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servico_medicoes', function (Blueprint $table) {
            $table->string('horaFinal')->nullable()->after('servico_id');
            $table->string('horaInicial')->nullable()->after('servico_id');
            $table->string('dataExecucao')->nullable()->after('servico_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servico_medicoes', function (Blueprint $table) {
            $table->dropColumn('dataExecucao');
            $table->dropColumn('horaInicial');
            $table->dropColumn('horaFinal');
        });
    }
}
