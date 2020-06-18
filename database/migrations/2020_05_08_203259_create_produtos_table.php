<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('tipoproduto_id')->nullable();
            $table->foreign('tipoproduto_id')->references('id')->on('tipoprodutos')->onDelete('cascade');
            $table->string('codigo')->nullable();
            $table->string('descricao');
            $table->unsignedBigInteger('unidademedida_id')->nullable();
            $table->foreign('unidademedida_id')->references('id')->on('unidademedidas')->onDelete('cascade');
            $table->string('codigobarra')->nullable();
            $table->string('validade')->nullable();
            $table->string('grupo')->nullable();
            $table->string('observacoes')->nullable();
            $table->float('valorcusto')->nullable();
            $table->float('valorvenda')->nullable();
            $table->float('ultimopreco')->nullable();
            $table->integer('estoqueminimo')->nullable();
            $table->integer('estoquemaximo')->nullable();
            $table->integer('quantidadeestoque')->nullable();
            $table->string('armazem')->nullable();
            $table->string('localizacaofisica')->nullable();
            $table->unsignedBigInteger('fornecedor_id')->nullable();
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->unsignedBigInteger('marca_id')->nullable();
            $table->foreign('marca_id')->references('id')->on('marcas')->onDelete('cascade');
            $table->string('datacompra')->nullable();
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
        Schema::dropIfExists('produtos');
    }
}
