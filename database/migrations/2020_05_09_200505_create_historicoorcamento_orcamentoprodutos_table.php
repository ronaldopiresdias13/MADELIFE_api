<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoorcamentoOrcamentoprodutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historicoorcamento_orcamentoprodutos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamentoproduto')->constrained()->onDelete('cascade');
            $table->foreignId('historicoorcamento')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('historicoorcamento_orcamentoprodutos');
    }
}
