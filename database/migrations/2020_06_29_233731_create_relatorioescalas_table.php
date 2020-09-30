<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatorioescalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relatorioescalas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('escala_id')->nullable();
            $table->foreign('escala_id')->references('id')->on('escalas')->onDelete('cascade');
            $table->string('caminho');
            $table->string('nome');
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
        Schema::dropIfExists('relatorioescalas');
    }
}
