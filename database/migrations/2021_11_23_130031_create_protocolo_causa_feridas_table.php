<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloCausaFeridasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo_causa_feridas', function (Blueprint $table) {
            $table->id();
            $table->boolean('circulacao_prejudicada')->nullable();
            $table->boolean('conhecimento_insuf')->nullable();
            $table->boolean('integridade_inflamacao')->nullable();
            $table->boolean('lesao_tecido_subcutaneo')->nullable();
            $table->boolean('lesao_tecido_epidermico')->nullable();
            $table->boolean('tumores')->nullable();
            $table->boolean('fratura')->nullable();
            $table->boolean('integridade_trauma')->nullable();
            $table->boolean('edema')->nullable();
            $table->boolean('exp_umidade_excessiva')->nullable();
            $table->boolean('mudancas_turgor_pele')->nullable();
            $table->boolean('proeminencias_osseas')->nullable();
            $table->boolean('ulceras_pressao')->nullable();
            $table->boolean('fragilidade_pele')->nullable();
            $table->boolean('imobilidade')->nullable();
            $table->boolean('ferida_cirurgica')->nullable();
            $table->boolean('ulceras_etiologias')->nullable();
            $table->boolean('doencas_pele')->nullable();
            $table->string('integridade_outro')->nullable();
            $table->boolean('agente_farmaceutico')->nullable();
            $table->boolean('conhecimento_insu_expo_patogenos')->nullable();
            $table->boolean('defesas_primarias')->nullable();
            $table->boolean('defesas_secundarias')->nullable();
            $table->boolean('infeccao_desnutricao')->nullable();
            $table->boolean('destruicao_tecidos')->nullable();
            $table->boolean('doenca_cronica')->nullable();
            $table->boolean('expo_ambiental')->nullable();
            $table->boolean('imunidade_adquirida')->nullable();
            $table->boolean('imunossupressao')->nullable();
            $table->boolean('procedimentos_invasivos')->nullable();
            $table->boolean('tecidos_inviaveis')->nullable();
            $table->boolean('infeccao_trauma')->nullable();
            $table->string('risco_infec_outro')->nullable();
            $table->boolean('aumento_temp')->nullable();
            $table->boolean('dimunuicao_temp')->nullable();
            $table->boolean('aumento_taxa')->nullable();
            $table->boolean('diminuicao_taxa')->nullable();
            $table->boolean('temperatura_desnutricao')->nullable();
            $table->boolean('desidratacao')->nullable();
            $table->boolean('dimin_capacidade_transpirar')->nullable();
            $table->boolean('doenca')->nullable();
            $table->boolean('ambiente_quente')->nullable();
            $table->boolean('ambiente_frio')->nullable();
            $table->boolean('medicamentos')->nullable();
            $table->boolean('temperatura_trauma')->nullable();
            $table->boolean('vestimentas_inadequadas')->nullable();
            $table->boolean('dano_hipotalamo')->nullable();
            $table->boolean('inatividade')->nullable();
            $table->boolean('envelhecimento')->nullable();
            $table->boolean('capac_tremer')->nullable();
            $table->string('alt_temp_outro')->nullable();
            $table->boolean('lesao_tecidual')->nullable();
            $table->boolean('diagnostico_cirurgia')->nullable();
            $table->boolean('infeccoes')->nullable();
            $table->string('diagnostico_vazio_outro')->nullable();
            $table->string('dor_aguda_obs')->nullable();
            $table->boolean('cancer')->nullable();
            $table->boolean('doencas_arteriais')->nullable();
            $table->boolean('infeccao')->nullable();
            $table->string('dor_cronica_outro')->nullable();
            $table->boolean('equipamento_externo')->nullable();
            $table->boolean('forca_insuficiente')->nullable();
            $table->boolean('fadiga')->nullable();
            $table->boolean('cirurgias')->nullable();
            $table->boolean('inconsciencia')->nullable();
            $table->boolean('andar_aclive')->nullable();
            $table->boolean('andar_declive')->nullable();
            $table->boolean('andar_superficies_irregulares')->nullable();
            $table->boolean('percorrer_distancia_necessarias')->nullable();
            $table->boolean('subir_escadas')->nullable();
            $table->boolean('forca_muscular_insuficiente')->nullable();
            $table->boolean('humor_deprimido')->nullable();
            $table->boolean('prejuizo_musculo_esqueletico')->nullable();
            $table->boolean('prejuizo_neuromuscular')->nullable();
            $table->boolean('medo_cair')->nullable();
            $table->boolean('obesidade')->nullable();
            $table->boolean('visao_prejudicada')->nullable();
            $table->string('mobilidade_fisica_outro')->nullable();
            $table->boolean('alto_nivel_estresse')->nullable();
            $table->boolean('ansiedade')->nullable();
            $table->boolean('radiacao')->nullable();
            $table->boolean('abuso_laxantes')->nullable();
            $table->boolean('alimentacao_sonda')->nullable();
            $table->boolean('contaminacao')->nullable();
            $table->boolean('efeitos_medicacoes')->nullable();
            $table->boolean('inflamacao')->nullable();
            $table->boolean('irritacao')->nullable();
            $table->boolean('mal_absorcao')->nullable();
            $table->boolean('parasitas')->nullable();
            $table->boolean('processos_infecciosos')->nullable();
            $table->string('eliminicacoes_fisiologicas_outro')->nullable();
            $table->boolean('aumento_peso')->nullable();
            $table->boolean('perda_peso')->nullable();
            $table->boolean('saude_fisica_instavel')->nullable();
            $table->boolean('saude_mental_instavel')->nullable();
            $table->boolean('estresse')->nullable();
            $table->boolean('falta_aceitacao_diagnostico')->nullable();
            $table->boolean('falta_adesao_diabete')->nullable();
            $table->boolean('falta_controle_diabete')->nullable();
            $table->boolean('ingestao_alimentar_inadequada')->nullable();
            $table->boolean('monitoracao_inadequada_glicemia')->nullable();
            $table->boolean('nivel_fisica_inadequada')->nullable();
            $table->string('risco_glicemia_outro')->nullable();
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
        Schema::dropIfExists('protocolo_causa_feridas');
    }
}
