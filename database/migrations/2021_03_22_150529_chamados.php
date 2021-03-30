<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Chamados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prestador_id')->nullable();
            $table->foreign('prestador_id')->references('id')->on('pessoas')->onDelete('cascade');
           
            $table->unsignedBigInteger('criador_id')->nullable();
            $table->foreign('criador_id')->references('id')->on('pessoas')->onDelete('cascade');
           
            $table->string('assunto')->nullable();
            $table->string('mensagem_inicial')->nullable();

            $table->boolean('finalizado')->default(false);
            $table->string('justificativa')->nullable();
            $table->string('protocolo')->nullable();

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
        Schema::dropIfExists('chamados');
    }
}
