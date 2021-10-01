<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanilhaAnexoASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planilhas_anexo_a', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('cascade');

            $table->foreignUuid('diagnostico_principal_id')->references('id')->on('diagnosticos_pil')->onDelete('cascade');

            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            
            $table->integer('classificacao_escala_braden');
            $table->integer('classificacao_coma_glasgow');

            $table->string('intensidade_dor');
            $table->string('diametros_pupilas');



            $table->timestamp('data_avaliacao');

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
        Schema::dropIfExists('planilhas_anexo_a');
    }
}
