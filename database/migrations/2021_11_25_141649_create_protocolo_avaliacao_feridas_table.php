<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloAvaliacaoFeridasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo_avaliacao_ferida', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('protocolo_id');
            $table->foreign('protocolo_id')->references('id')->on('protocolo_skin')->onDelete('cascade');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('protocololesao_id')->nullable();
            $table->foreign('protocololesao_id')->references('id')->on('protocolo_avaliacao_lesao_pressao')->onDelete('cascade');
            $table->unsignedBigInteger('protocolopesdiabeticos_id')->nullable();
            $table->foreign('protocolopesdiabeticos_id')->references('id')->on('protocolo_avaliacao_pes_diabeticos')->onDelete('cascade');
            $table->unsignedBigInteger('protocololaserterapia_id')->nullable();
            $table->foreign('protocololaserterapia_id')->references('id')->on('avaliacaolaserterapia')->onDelete('cascade');
            $table->boolean('ferida_cirurgica')->nullable();
            $table->boolean('ferida_lesao_pressao')->nullable();
            $table->boolean('ferida_diabetica')->nullable();
            $table->boolean('ferida_venosa')->nullable();
            $table->boolean('ferida_arterial')->nullable();
            $table->string('ferida_outros')->nullable();
            $table->string('tempo_lesao')->nullable();
            $table->boolean('feridas_anteriores')->nullable();
            $table->boolean('popliteo_d')->nullable();
            $table->boolean('popliteo_e')->nullable();
            $table->boolean('pedial_d')->nullable();
            $table->boolean('pedial_e')->nullable();
            $table->boolean('perfusao_menor')->nullable();
            $table->boolean('perfusao_maior')->nullable();
            $table->string('mensuracao_anterior')->nullable();
            $table->string('mensuracao_atual')->nullable();
            $table->boolean('lp_1')->nullable();
            $table->boolean('lp_2')->nullable();
            $table->boolean('lp_3')->nullable();
            $table->boolean('lp_4')->nullable();
            $table->boolean('lp_dispositivo_medico')->nullable();
            $table->boolean('lp_ltp')->nullable();
            $table->boolean('lp_impossivel_estadiar')->nullable();
            $table->boolean('skin_1')->nullable();
            $table->boolean('skin_2')->nullable();
            $table->boolean('skin_3')->nullable();
            $table->boolean('venosa_superficial')->nullable();
            $table->boolean('venosa_parcial')->nullable();
            $table->boolean('venosa_total')->nullable();
            $table->string('leitof_granulacao')->nullable();
            $table->string('leitof_esfacelo')->nullable();
            $table->string('leitof_necrose')->nullable();
            $table->string('leitof_biofilme')->nullable();
            $table->string('leitof_outros')->nullable();
            $table->boolean('leitof_gangrena')->nullable();
            $table->boolean('desbridamento_autolitico')->nullable();
            $table->boolean('desbridamento_enzimatico')->nullable();
            $table->boolean('desbridamento_mecanico')->nullable();
            $table->boolean('desbridamento_cirurgico')->nullable();
            $table->boolean('margem_epitelizada')->nullable();
            $table->boolean('margem_epitelizando')->nullable();
            $table->boolean('margem_necrosada')->nullable();
            $table->boolean('margem_hiperemiada')->nullable();
            $table->boolean('margem_epibole')->nullable();
            $table->boolean('margem_aderida')->nullable();
            $table->boolean('margem_descolada_total')->nullable();
            $table->boolean('margem_descolada_parcial')->nullable();
            $table->string('descolada_parcial_hrs')->nullable();
            $table->string('descolada_parcial_cm')->nullable();
            $table->string('margem_outros')->nullable();
            $table->boolean('pele_hiperemiada')->nullable();
            $table->boolean('pele_ressecada')->nullable();
            $table->boolean('pele_queratose')->nullable();
            $table->boolean('pele_eczema')->nullable();
            $table->boolean('pele_lesoes')->nullable();
            $table->boolean('pele_dermatite')->nullable();
            $table->string('pele_outro')->nullable();
            $table->boolean('exsudato_seroso')->nullable();
            $table->boolean('exsudato_translucido')->nullable();
            $table->boolean('exsudato_sanguinolento')->nullable();
            $table->boolean('exsudato_serosanguinolento')->nullable();
            $table->boolean('exsudato_purulento')->nullable();
            $table->boolean('exsudato_piosanguinolento')->nullable();
            $table->boolean('exsudato_esverdeado')->nullable();
            $table->string('exsudato_outro')->nullable();
            $table->boolean('volume_escasso')->nullable();
            $table->boolean('volume_pequeno')->nullable();
            $table->boolean('volume_moderado')->nullable();
            $table->boolean('volume_grande')->nullable();
            $table->boolean('volume_abundante')->nullable();
            $table->boolean('coleta_cultura')->nullable();
            $table->string('coleta_cultura_resultado')->nullable();
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
        Schema::dropIfExists('protocolo_avaliacao_ferida');
    }
}
