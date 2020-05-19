<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuidadoEscalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuidado_escalas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('escala_id');
            $table->foreign('escala_id')->references('id')->on('escalas')->onDelete('cascade');
            $table->unsignedBigInteger('cuidado_id');
            $table->foreign('cuidado_id')->references('id')->on('cuidados')->onDelete('cascade');
            $table->string('data')->nullable();
            $table->string('hora')->nullable();
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
        Schema::dropIfExists('cuidado_escalas');
    }
}
