<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcaomedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acaomedicamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transcricao_produto_id');
            $table->foreign('transcricao_produto_id')->references('id')->on('transcricao_produto')->onDelete('cascade');
            $table->unsignedBigInteger('prestador_id');
            $table->foreign('prestador_id')->references('id')->on('prestadores')->onDelete('cascade');
            $table->string('data')->nullable();
            $table->string('hora')->nullable();
            $table->boolean('status')->nullable();
            $table->string('observacao')->nullable();
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
        Schema::dropIfExists('acaomedicamentos');
    }
}
