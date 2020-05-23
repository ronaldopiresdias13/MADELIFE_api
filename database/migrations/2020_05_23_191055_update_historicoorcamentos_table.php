<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHistoricoorcamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicoorcamentos', function (Blueprint $table) {
            $table->dropColumn('data');
            $table->dropColumn('valortotalservico');
            $table->dropColumn('valortotalproduto');
            $table->dropColumn('valortotalcusto');
            $table->string('historico')->after('orcamento_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historicoorcamentos', function (Blueprint $table) {
            $table->float('valortotalcusto')->after('orcamento_id')->nullable();
            $table->float('valortotalproduto')->after('orcamento_id')->nullable();
            $table->float('valortotalservico')->after('orcamento_id')->nullable();
            $table->string('data')->after('orcamento_id')->nullable();
            $table->dropColumn('historico');
        });
    }
}
