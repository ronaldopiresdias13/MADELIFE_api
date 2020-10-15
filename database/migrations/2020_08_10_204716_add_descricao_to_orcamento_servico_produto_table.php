<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescricaoToOrcamentoServicoProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $table->longText('descricao')->nullable()->after('inss');
        });
        Schema::table('orcamento_produto', function (Blueprint $table) {
            $table->longText('descricao')->nullable()->after('locacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $table->dropColumn('descricao');
        });
        Schema::table('orcamento_produto', function (Blueprint $table) {
            $table->dropColumn('descricao');
        });
    }
}
