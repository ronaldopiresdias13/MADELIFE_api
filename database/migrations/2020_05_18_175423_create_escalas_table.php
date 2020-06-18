<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escalas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedBigInteger('ordemservico_id');
            $table->foreign('ordemservico_id')->references('id')->on('ordemservicos')->onDelete('cascade');
            $table->unsignedBigInteger('prestador_id');
            $table->foreign('prestador_id')->references('id')->on('prestadores')->onDelete('cascade');
            $table->unsignedBigInteger('servico_id')->nullable();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');
            $table->string('horaentrada')->nullable();
            $table->string('horasaida')->nullable();
            $table->string('dataentrada')->nullable();
            $table->string('datasaida')->nullable();
            $table->string('periodo')->nullable();
            $table->string('assinaturaprestador')->nullable();
            $table->string('assinaturaresonsavel')->nullable();
            $table->string('observacao')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('folga')->nullable();
            $table->unsignedBigInteger('substituto')->nullable();
            $table->foreign('substituto')->references('id')->on('prestadores')->onDelete('cascade');
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
        Schema::dropIfExists('escalas');
    }
}
