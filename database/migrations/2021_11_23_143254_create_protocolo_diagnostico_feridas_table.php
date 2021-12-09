<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloDiagnosticoFeridasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo_diagnostico_ferida', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('protocolocausa_id');
            $table->foreign('protocolocausa_id')->references('id')->on('protocolo_causa_feridas');
            $table->unsignedBigInteger('protocolosintomas_id');
            $table->foreign('protocolosintomas_id')->references('id')->on('protocolo_sinais_sintoma_feridas');
            $table->boolean('integridade_tissular')->nullable();
            $table->boolean('risco_infeccao')->nullable();
            $table->boolean('alt_temperatura')->nullable();
            $table->boolean('diagnostico_vazio')->nullable();
            $table->boolean('dor_aguda')->nullable();
            $table->boolean('dor_cronica')->nullable();
            $table->boolean('mobilidade_fisica')->nullable();
            $table->boolean('alt_eliminacoes')->nullable();
            $table->boolean('risco_glicemia')->nullable();
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
        Schema::dropIfExists('protocolo_diagnostico_ferida');
    }
}
