<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicoMedicoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servico_medicoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicoes_id');
            $table->foreign('medicoes_id')->references('id')->on('medicoes')->onDelete('cascade');
            $table->unsignedBigInteger('servico_id');
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');
            $table->string('quantidade')->nullable();
            $table->string('atendido')->nullable();
            $table->string('valor')->nullable();
            $table->string('subtotal')->nullable();
            $table->string('situacao')->nullable();
            $table->string('observacao')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('servico_medicoes');
    }
}
