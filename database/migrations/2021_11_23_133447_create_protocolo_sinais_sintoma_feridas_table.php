<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloSinaisSintomaFeridasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo_sinais_sintoma_feridas', function (Blueprint $table) {
            $table->id();
            $table->boolean('incisao_cirurgica')->nullable();
            $table->boolean('hematoma')->nullable();
            $table->boolean('hiperemia')->nullable();
            $table->boolean('edema')->nullable();
            $table->boolean('calor')->nullable();
            $table->boolean('turgor')->nullable();
            $table->boolean('perda_solucao_continuidade')->nullable();
            $table->boolean('estase_fluidos_organico')->nullable();
            $table->boolean('mudanca_ph_secrecoes')->nullable();
            $table->string('integridade_risco_sintomas_outro')->nullable();
            $table->boolean('exsudado_purulento')->nullable();
            $table->boolean('exsudado_odor_fetido')->nullable();
            $table->boolean('risco_infeccao_taquicardia')->nullable();
            $table->boolean('hiperemia_maior')->nullable();
            $table->boolean('celulite')->nullable();
            $table->boolean('calor_local')->nullable();
            $table->boolean('alt_temp_corporal')->nullable();
            $table->boolean('dor')->nullable();
            $table->boolean('tecidos_inviaveis')->nullable();
            $table->string('risco_infeccao_sintomas_outro')->nullable();
            $table->boolean('hipertemia')->nullable();
            $table->boolean('hipotermia')->nullable();
            $table->boolean('convulsoes')->nullable();
            $table->boolean('pele_avermelhada')->nullable();
            $table->boolean('taquicardia')->nullable();
            $table->boolean('taquipneia')->nullable();
            $table->boolean('cianose_leitos_ungueais')->nullable();
            $table->boolean('hipertensao')->nullable();
            $table->boolean('hipotensao')->nullable();
            $table->boolean('hipoglicemia')->nullable();
            $table->boolean('palidez')->nullable();
            $table->boolean('pele_fria')->nullable();
            $table->boolean('piloerecao')->nullable();
            $table->boolean('capilar_lento')->nullable();
            $table->boolean('tremor')->nullable();
            $table->string('alt_temp_corpo_sintomas_outro')->nullable();
            $table->boolean('relato_verbal')->nullable();
            $table->boolean('expre_facial_dor')->nullable();
            $table->boolean('incap_realizar_acoes')->nullable();
            $table->string('diagnostico_vazio_sintomas_outro')->nullable();
            $table->boolean('dor_cronica_relato_verbal')->nullable();
            $table->boolean('dor_cronica_expre_facial_dor')->nullable();
            $table->boolean('dor_cronica_incap_realizar_acoes')->nullable();
            $table->string('dor_cronica_sintomas_outro')->nullable();
            $table->boolean('restric_imposta_movi')->nullable();
            $table->boolean('capac_motora_prejudicada')->nullable();
            $table->boolean('inconsciencia')->nullable();
            $table->boolean('mobilidade_dor')->nullable();
            $table->boolean('equilibrio_prejudicado')->nullable();
            $table->string('mobilidade_fisica_sintomas_outro')->nullable();
            $table->boolean('tres_evacuacoes_fezes')->nullable();
            $table->boolean('urgencia_evacuar')->nullable();
            $table->string('alt_eliminacoes_sintomas_outro')->nullable();
            $table->boolean('perda_peso')->nullable();
            $table->boolean('aumento_peso')->nullable();
            $table->boolean('sede')->nullable();
            $table->string('risco_glicemia_sintomas_outro')->nullable();
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
        Schema::dropIfExists('protocolo_sinais_sintoma_feridas');
    }
}
