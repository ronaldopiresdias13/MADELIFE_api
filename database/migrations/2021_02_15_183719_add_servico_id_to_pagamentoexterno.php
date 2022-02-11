<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServicoIdToPagamentoexterno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagamentoexternos', function (Blueprint $table) {
            $table->unsignedBigInteger('servico_id')->nullable()->after('pessoa_id');
            $table->foreign('servico_id')->references('id')->on('servicos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagamentoexternos', function (Blueprint $table) {
            $table->dropForeign(['servico_id']);
            $table->dropColumn('servico_id');
        });
    }
}
