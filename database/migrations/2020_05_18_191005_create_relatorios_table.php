<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relatorios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('escala_id');
            $table->foreign('escala_id')->references('id')->on('escalas')->onDelete('cascade');
            $table->string('data')->nullable();
            $table->string('hora')->nullable();
            $table->string('quadro')->nullable();
            $table->string('tipo');
            $table->longText('texto')->nullable();
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
        Schema::dropIfExists('relatorios');
    }
}
