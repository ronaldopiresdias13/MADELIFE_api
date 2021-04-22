<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteUuidOfSaidasSaidaProdutoAndVendaSaida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locacoes', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        Schema::table('saidas', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        Schema::table('entradas', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        Schema::table('entrada_produto', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locacoes', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });
        Schema::table('saidas', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });
        Schema::table('entradas', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });
        Schema::table('entrada_produto', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });
        Schema::table('vendas', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });
    }
}
