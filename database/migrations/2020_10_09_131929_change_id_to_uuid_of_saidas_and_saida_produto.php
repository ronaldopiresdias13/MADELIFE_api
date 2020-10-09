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
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->dropForeign(['saida_id']);
        });

        Schema::table('saida_produto', function (Blueprint $table) {
            $table->uuid('saida_id')->change();
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->dropForeign(['saida_id']);
        });

        Schema::table('venda_saida', function (Blueprint $table) {
            $table->uuid('saida_id')->change();
        });
        Schema::table('saidas', function (Blueprint $table) {
            $table->uuid('id')->change();
        });
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->foreign('saida_id')->references('id')->on('saidas')->onDelete('cascade');
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->foreign('saida_id')->references('id')->on('saidas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->dropForeign(['saida_id']);
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->dropForeign(['saida_id']);
        });
        Schema::table('saidas', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->unsignedBigInteger('saida_id')->change();
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->unsignedBigInteger('saida_id')->change();
        });
        Schema::table('saida_produto', function (Blueprint $table) {
            $table->foreign('saida_id')->references('id')->on('saidas')->onDelete('cascade');
        });
        Schema::table('venda_saida', function (Blueprint $table) {
            $table->foreign('saida_id')->references('id')->on('saidas')->onDelete('cascade');
        });
    }
}
