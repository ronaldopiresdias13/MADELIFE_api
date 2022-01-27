<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanilhaPilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planilhas_pils', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('cascade');

            $table->foreignUuid('diagnostico_primario_id')->references('id')->on('diagnosticos_pil')->onDelete('cascade');

            $table->text('revisao')->nullable();
            $table->text('prognostico')->nullable();
            $table->text('avaliacao_prescricoes')->nullable();
            $table->text('justificativa_revisao')->nullable();
            $table->text('evolucao_base')->nullable();


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
        Schema::dropIfExists('planilhas_pils');
    }
}
