<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificadoprestadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificadoprestadores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prestador_id')->nullable();
            $table->foreign('prestador_id')->references('id')->on('prestadores')->onDelete('cascade');
            $table->string('caminho')->nullable();
            $table->string('nome')->nullable();
            $table->string('data')->nullable();
            $table->string('carga')->nullable();
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
        Schema::dropIfExists('certificadoprestadores');
    }
}
