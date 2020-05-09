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
            $table->string('tipo');
            $table->foreignId('cliente')->constrained()->onDelete('cascade');
            $table->foreignId('empresa')->constrained()->onDelete('cascade');
            $table->string('data');
            $table->integer('quantidade');
            $table->integer('unidade');
            $table->string('cidade')->nullable();
            $table->string('processo')->nullable();
            $table->string('situacao');
            $table->string('descricao')->nullable();
            $table->string('observacao')->nullable();
            $table->boolean('status')->default(true);
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
