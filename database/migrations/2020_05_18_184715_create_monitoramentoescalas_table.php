<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoramentoescalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoramentoescalas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('escala_id');
            $table->foreign('escala_id')->references('id')->on('escalas')->onDelete('cascade');
            $table->string('datahora');
            $table->string('pa')->nullable();
            $table->string('p')->nullable();
            $table->string('t')->nullable();
            $table->string('fr')->nullable();
            $table->string('sat')->nullable();
            $table->string('criev')->nullable();
            $table->string('ev')->nullable();
            $table->string('dieta')->nullable();
            $table->string('cridieta')->nullable();
            $table->string('criliquido')->nullable();
            $table->string('liquido')->nullable();
            $table->string('cridiurese')->nullable();
            $table->string('diurese')->nullable();
            $table->string('evac')->nullable();
            $table->string('crievac')->nullable();
            $table->string('crivomito')->nullable();
            $table->string('vomito')->nullable();
            $table->string('asp')->nullable();
            $table->string('decub')->nullable();
            $table->boolean('curativo');
            $table->boolean('fraldas');
            $table->boolean('sondas');
            $table->boolean('dextro');
            $table->boolean('o2');
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
        Schema::dropIfExists('monitoramentoescalas');
    }
}
