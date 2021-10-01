<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HorariosMedicamentosPil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios_medicamentos_pil', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('pil_id')->references('id')->on('planilhas_pils')->onDelete('cascade');
            $table->unsignedBigInteger('medicamento_pil_id');
            $table->foreign('medicamento_pil_id')->references('id')->on('medicamentos_pil')->onDelete('cascade');
            $table->string('horario');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horarios_medicamentos_pil');
    }
}
