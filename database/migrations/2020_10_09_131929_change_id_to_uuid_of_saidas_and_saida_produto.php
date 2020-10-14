<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdToUuidOfSaidasAndSaidaProduto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locacoes', function (Blueprint $table) {
            $table->dropForeign(['venda_id']);
        });
        Schema::table('locacoes', function (Blueprint $table) {
            $table->uuid('venda_id')->change();
        });
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->dropForeign(['saida_id']);
        });
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->uuid('saida_id')->change();
        });

        Schema::table('venda_saida', function (Blueprint $table) {
            $table->dropForeign(['saida_id']);
            $table->dropForeign(['venda_id']);
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->uuid('saida_id')->change();
            $table->uuid('venda_id')->change();
        });



        Schema::table('saidas', function (Blueprint $table) {
            $table->uuid('id')->change();
        });
        Schema::table('vendas', function (Blueprint $table) {
            $table->uuid('id')->change();
        });
        Schema::table('locacoes', function (Blueprint $table) {
            $table->uuid('id')->change();
        });


        Schema::table('saida_produto', function (Blueprint $table) {
            $table->foreign('saida_id')->references('id')->on('saidas')->onDelete('cascade');
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->foreign('saida_id')->references('id')->on('saidas')->onDelete('cascade');
            $table->foreign('venda_id')->references('id')->on('saidas')->onDelete('cascade');
        });
        Schema::table('locacoes', function (Blueprint $table) {
            $table->foreign('venda_id')->references('id')->on('saidas')->onDelete('cascade');
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
            $table->dropForeign(['venda_id']);
        });
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->dropForeign(['saida_id']);
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->dropForeign(['saida_id']);
            $table->dropForeign(['venda_id']);
        });

        Schema::table('locacoes', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('saidas', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('vendas', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });


        Schema::table('locacoes', function (Blueprint $table) {
            $table->unsignedBigInteger('venda_id')->change();
        });
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->unsignedBigInteger('saida_id')->change();
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->unsignedBigInteger('saida_id')->change();
            $table->unsignedBigInteger('venda_id')->change();
        });



        Schema::table('locacoes', function (Blueprint $table) {
            $table->foreign('venda_id')->references('id')->on('saidas')->onDelete('cascade');
        });
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->foreign('saida_id')->references('id')->on('saidas')->onDelete('cascade');
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->foreign('saida_id')->references('id')->on('saidas')->onDelete('cascade');
            $table->foreign('venda_id')->references('id')->on('saidas')->onDelete('cascade');
        });
    }
}
