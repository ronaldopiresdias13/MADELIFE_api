<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocacaoToOrcamentoProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamento_produto', function (Blueprint $table) {
            $table->boolean('locacao')->default(0)->after('valorcustomensal');
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
            $table->dropColumn('locacao');
        });
    }
}
