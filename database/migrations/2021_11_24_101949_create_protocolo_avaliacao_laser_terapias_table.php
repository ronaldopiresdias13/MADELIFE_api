<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloAvaliacaoLaserTerapiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliacaolaserterapia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pro_diagnostico_id');
            $table->foreign('pro_diagnostico_id')->references('id')->on('protocolo_diagnostico_ferida');
            $table->string('laserterapia_localizacao')->nullable();
            $table->boolean('tratamento_ferida_cirurgica')->nullable();
            $table->boolean('tratamento_ferida_lesao_pressao')->nullable();
            $table->boolean('tratamento_ferida_neuropatica')->nullable();
            $table->boolean('tratamento_ferida_venosa')->nullable();
            $table->boolean('tratamento_ferida_arterial')->nullable();
            $table->string('tratamento_ferida_outros')->nullable();
            $table->string('estadiamento')->nullable();
            $table->string('mensuracao')->nullable();
            $table->string('leito_ferida')->nullable();
            $table->string('margem_ferida')->nullable();
            $table->string('pele_redor')->nullable();
            $table->string('exsudato')->nullable();
            $table->integer('classificacao_dor')->nullable();
            $table->float('area_lesao_larg')->nullable();
            $table->float('area_lesao_comp')->nullable();
            $table->float('energia_total_j')->nullable();
            $table->float('energia_total_area')->nullable();
            $table->boolean('pontos_area_afetada')->nullable();
            $table->string('area_afetada_pontos')->nullable();
            $table->boolean('area_afetada_v')->nullable();
            $table->boolean('area_afetada_if')->nullable();
            $table->boolean('area_afetada_pontual')->nullable();
            $table->boolean('area_afetada_varredura')->nullable();
            $table->boolean('pontos_redor_area')->nullable();
            $table->string('redor_area_pontos')->nullable();
            $table->boolean('redor_area_v')->nullable();
            $table->boolean('redor_area_if')->nullable();
            $table->string('escala_dor_obs')->nullable();
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
        Schema::dropIfExists('protocolo_avaliacao_laser_terapia');
    }
}
