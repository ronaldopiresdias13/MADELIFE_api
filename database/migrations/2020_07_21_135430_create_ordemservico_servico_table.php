<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdemservicoServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordemservico_servico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordemservico_id');
            $table->foreign('ordemservico_id')->references('id')->on('ordemservicos')->onDelete('cascade');
            $table->unsignedBigInteger('servico_id');
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');
            $table->string('descricao');
            $table->float('valor');
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
        Schema::dropIfExists('ordemservico_servico');
    }
}
