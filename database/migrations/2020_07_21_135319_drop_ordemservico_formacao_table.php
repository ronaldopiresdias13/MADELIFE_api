<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOrdemservicoFormacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ordemservico_formacao');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('ordemservico_formacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordemservico_id');
            $table->foreign('ordemservico_id')->references('id')->on('ordemservicos')->onDelete('cascade');
            $table->unsignedBigInteger('formacao_id');
            $table->foreign('formacao_id')->references('id')->on('formacoes')->onDelete('cascade');
            $table->string('descricao');
            $table->float('valor');
            $table->boolean('ativo')->default(true);
            $table->index('ativo');
            $table->timestamps();
        });
    }
}
