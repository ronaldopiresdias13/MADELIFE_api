<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrcamentoIdToOrcamentoProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamento_produto', function (Blueprint $table) {
            $table->unsignedBigInteger('orcamento_id')->after('produto_id');
            $table->foreign('orcamento_id')->references('id')->on('orcamentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamento_produto', function (Blueprint $table) {
            $table->dropForeign('orcamento_produto_orcamento_id_foreign');
            $table->dropColumn('orcamento_id');
        });
    }
}