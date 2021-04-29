<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OcorrenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ocorrencias', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('pessoa_id')->nullable();
            // $table->foreign('pessoa_id')->references('id')->on('pessoas')->onDelete('cascade');
           
            // $table->unsignedBigInteger('criador_id')->nullable();
            // $table->foreign('criador_id')->references('id')->on('pessoas')->onDelete('cascade');
           
            $table->string('tipo');
            $table->string('situacao');

            $table->unsignedBigInteger('transcricao_produto_id')->nullable();
            $table->foreign('transcricao_produto_id')->references('id')->on('transcricao_produto')->onDelete('cascade');

            $table->unsignedBigInteger('escala_id')->nullable();
            $table->foreign('escala_id')->references('id')->on('escalas')->onDelete('cascade');
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
        Schema::dropIfExists('ocorrencias');
    }
}
