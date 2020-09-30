<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotacaoProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotacao_produto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cotacao_id');
            $table->foreign('cotacao_id')->references('id')->on('cotacoes')->onDelete('cascade');
            $table->unsignedBigInteger('produto_id');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->unsignedBigInteger('fornecedor_id');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->string('unidademedida');
            $table->float('quantidade');
            $table->float('quantidadeembalagem');
            $table->float('quantidadetotal');
            $table->float('valorunitario');
            $table->float('valortotal');
            $table->string('observacao');
            $table->string('situacao');
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
        Schema::dropIfExists('cotacao_produto');
    }
}
