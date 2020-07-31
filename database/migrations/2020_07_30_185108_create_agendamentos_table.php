<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('profissional_id');
            $table->foreign('profissional_id')->references('id')->on('profissionais')->onDelete('cascade');
            $table->uuid('sala_id');
            $table->foreign('sala_id')->references('id')->on('salas')->onDelete('cascade');
            $table->string('nome')->nullable();
            $table->string('descricao')->nullable();
            $table->string('cor')->nullable();
            $table->string('datainicio')->nullable();
            $table->string('datafim')->nullable();
            $table->string('horainicio')->nullable();
            $table->string('horafim')->nullable();
            $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('agendamentos');
    }
}
