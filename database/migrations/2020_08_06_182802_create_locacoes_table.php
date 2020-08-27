<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locacoes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('produto_id');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->unsignedBigInteger('venda_id');
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->float('quantidade')->nullable();
            $table->string('datainicio')->nullable();
            $table->string('datafim')->nullable();
            $table->string('status')->nullable();
            $table->string('contrato')->nullable();
            $table->string('nomecontrato')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locacoes');
    }
}
