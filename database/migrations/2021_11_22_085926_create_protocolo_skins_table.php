<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloSkinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo_skin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->string('medico')->nullable();
            $table->string('diagnostico')->nullable();
            $table->boolean('afastado')->nullable();
            $table->boolean('aposentado')->nullable();
            $table->string('data')->nullable();
            $table->string('nivel_consciencia')->nullable();
            $table->string('obs_nivel_consciencia')->nullable();
            $table->boolean('hipertensao_arterial')->nullable();
            $table->string('pa')->nullable();
            $table->boolean('diabetes')->nullable();
            $table->string('glicemia')->nullable();
            $table->string('hematocrito')->nullable();
            $table->string('hemoglobina')->nullable();
            $table->string('proteinas_totais')->nullable();
            $table->string('pcr')->nullable();
            $table->string('albumina')->nullable();
            $table->string('outros')->nullable();
            $table->string('peso')->nullable();
            $table->string('altura')->nullable();
            $table->boolean('acamado')->nullable();
            $table->boolean('deambula')->nullable();
            $table->boolean('cadeira_rodas')->nullable();
            $table->boolean('muleta')->nullable();
            $table->boolean('andador')->nullable();
            $table->boolean('c_ajuda')->nullable();
            $table->boolean('destreza_manual')->nullable();
            $table->boolean('auto_cuidado')->nullable();
            $table->boolean('cafe_manha')->nullable();
            $table->boolean('almoco')->nullable();
            $table->boolean('cafe_tarde')->nullable();
            $table->boolean('jantar')->nullable();
            $table->boolean('ceia')->nullable();
            $table->string('rica')->nullable();
            $table->string('urinaria')->nullable();
            $table->string('fecal')->nullable();
            $table->string('medicamento')->nullable();
            $table->boolean('av_central')->nullable();
            $table->boolean('av_periferico')->nullable();
            $table->boolean('av_jelco')->nullable();
            $table->boolean('av_scalp')->nullable();
            $table->boolean('av_intracath')->nullable();
            $table->boolean('av_portocath')->nullable();
            $table->boolean('av_piv')->nullable();
            $table->string('av_data')->nullable();
            $table->boolean('tbg_fuma')->nullable();
            $table->string('tbg_tempo')->nullable();
            $table->string('tbg_cigarros_dia')->nullable();
            $table->boolean('tbg_atual')->nullable();
            $table->string('tbg_tempo_parou')->nullable();
            $table->boolean('alergia')->nullable();
            $table->string('alergia_qual')->nullable();
            $table->boolean('oxigenioterapia')->nullable();
            $table->boolean('ox_cateter_nasal')->nullable();
            $table->boolean('ox_mascara_nebulizacao')->nullable();
            $table->boolean('ox_cateter_venturi')->nullable();
            $table->boolean('ox_bipap')->nullable();
            $table->boolean('ox_cpap')->nullable();
            $table->boolean('al_vo')->nullable();
            $table->boolean('al_sng')->nullable();
            $table->boolean('al_sne')->nullable();
            $table->boolean('al_gastrostomia')->nullable();
            $table->boolean('al_jejunostomia')->nullable();
            $table->boolean('al_parenteral')->nullable();
            $table->boolean('as_sozinho')->nullable();
            $table->boolean('as_familiares')->nullable();
            $table->boolean('as_cuidador')->nullable();
            $table->boolean('as_casa_terrea')->nullable();
            $table->boolean('as_apartamento')->nullable();
            $table->boolean('as_casa_escadas')->nullable();
            $table->string('as_outros')->nullable();
            $table->boolean('cp_normal')->nullable();
            $table->boolean('cp_seca')->nullable();
            $table->boolean('cp_oleosa')->nullable();
            $table->boolean('cp_mista')->nullable();
            $table->boolean('cp_ressecada')->nullable();
            $table->string('cp_outra')->nullable();
            $table->boolean('ds_visao')->nullable();
            $table->boolean('ds_audicao')->nullable();
            $table->boolean('cpa_pele_oleosa')->nullable();
            $table->boolean('cpa_cicatriz')->nullable();
            $table->boolean('cpa_dobras_gordura')->nullable();
            $table->boolean('cpa_dermatologica')->nullable();
            $table->boolean('cpa_atual')->nullable();
            $table->string('cpa_qual')->nullable();
            $table->boolean('crd_lesoes')->nullable();
            $table->boolean('crd_fistulas')->nullable();
            $table->boolean('crd_fisuras')->nullable();
            $table->boolean('crd_incontinencia')->nullable();
            $table->string('crd_outros')->nullable();
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
        Schema::dropIfExists('protocolo_skin');
    }
}
