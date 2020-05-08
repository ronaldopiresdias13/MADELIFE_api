<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->string('processo');
            $table->string('cidade');
            $table->string('tipo');
            $table->string('data');
            $table->string('situacao');
            $table->string('paciente');
            $table->string('descricao');
            $table->string('observacao');
            $table->integer('ciclomeses');
            $table->boolean('status')->default(true);
            $table->foreignId('cliente')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('orcamentos');
    }
}
