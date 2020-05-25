<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdemservicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->unsignedBigInteger('cliente_id')->after('tipo')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');

            // $table->dropColumn('orcamento_id');
            // $table->unsignedBigInteger('orcamento_id')->after('id');
            // $table->foreign('orcamento_id')->references('id')->on('orcamentos')->onDelete('cascade');
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
            $table->dropForeign('ordemservicos_cliente_id_foreign');
            $table->dropColumn('cliente_id');

            // $table->dropForeign('ordemservicos_orcamento_id_foreign');
            // $table->dropColumn('orcamento_id');
        });
    }
}
