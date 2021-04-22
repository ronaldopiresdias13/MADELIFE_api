<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicoFormacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servico_formacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servico_id')->nullable();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');
            $table->unsignedBigInteger('formacao_id')->nullable();
            $table->foreign('formacao_id')->references('id')->on('formacoes')->onDelete('cascade');
            $table->boolean('ativo')->default(true);
            $table->index('ativo');
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
        Schema::dropIfExists('servico_formacaos');
    }
}
