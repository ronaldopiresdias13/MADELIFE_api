<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorestotaisOrcamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->float('valortotalservico')->nullable()->after('descricao');
            $table->float('valortotalcusto')->nullable()->after('descricao');
            $table->float('valortotalproduto')->nullable()->after('descricao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn('valortotalservico');
            $table->dropColumn('valortotalcusto');
            $table->dropColumn('valortotalproduto');
        });
    }
}
