<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaPessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_pessoas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa')->constrained()->onDelete('cascade');
            $table->foreignId('pessoa')->constrained()->onDelete('cascade');
            $table->string('contrato');
            $table->string('inicio');
            $table->string('fim');
            $table->boolean('situacao');
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
        Schema::dropIfExists('empresa_pessoas');
    }
}
