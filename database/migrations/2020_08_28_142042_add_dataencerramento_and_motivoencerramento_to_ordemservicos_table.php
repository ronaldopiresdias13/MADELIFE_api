<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataencerramentoAndMotivoencerramentoToOrdemservicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->string('motivo')->nullable()->after('realizacaoprocedimento');
            $table->string('dataencerramento')->nullable()->after('realizacaoprocedimento');
            $table->string('descricaomotivo')->nullable()->after('realizacaoprocedimento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->dropColumn('motivo');
            $table->dropColumn('dataencerramento');
            $table->dropColumn('descricaomotivo');
        });
    }
}
