<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFolgasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folgas', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('escala_id');
            $table->foreign('escala_id')->references('id')->on('escalas');
            $table->unsignedBigInteger('prestador_id');
            $table->foreign('prestador_id')->references('id')->on('prestadores');
            $table->boolean('aprovada')->default(false);
            $table->unsignedBigInteger('substituto')->nullable();
            $table->foreign('substituto')->references('id')->on('prestadores');
            $table->string('datasolicitacao')->nullable();
            $table->string('dataaprovacao')->nullable();
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
        Schema::dropIfExists('folgas');
    }
}
