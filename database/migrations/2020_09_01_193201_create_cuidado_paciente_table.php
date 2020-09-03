<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuidadoPacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuidado_paciente', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('cuidado_id')->nullable();
            $table->foreign('cuidado_id')->references('id')->on('cuidados')->onDelete('cascade');
            $table->unsignedBigInteger('paciente_id')->nullable();
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('cascade');
            $table->unsignedBigInteger('formacao_id')->nullable();
            $table->foreign('formacao_id')->references('id')->on('formacoes')->onDelete('cascade');
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
        Schema::dropIfExists('cuidado_paciente');
    }
}
