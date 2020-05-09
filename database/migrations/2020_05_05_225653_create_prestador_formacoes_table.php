<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestadorFormacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestador_formacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prestador_id');
            $table->foreign('prestador_id')->references('id')->on('prestadores')->onDelete('cascade');
            $table->unsignedBigInteger('formacao_id');
            $table->foreign('formacao_id')->references('id')->on('formacoes')->onDelete('cascade');
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
        Schema::dropIfExists('prestador_formacoes');
    }
}
