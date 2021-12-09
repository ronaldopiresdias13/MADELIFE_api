<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloAvaliacaoEstomasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo_avaliacao_estoma', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('protocolo_id');
            $table->foreign('protocolo_id')->references('id')->on('protocolo_skin');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->boolean('cl_traqueostomia')->nullable();
            $table->boolean('cl_gastrostomia')->nullable();
            $table->boolean('cl_jejunostomia')->nullable();
            $table->boolean('cl_ileostomia')->nullable();
            $table->boolean('cl_colostomiad')->nullable();
            $table->boolean('cl_colostomiae')->nullable();
            $table->boolean('cl_urostomia')->nullable();
            $table->boolean('cl_cistostomia')->nullable();
            $table->string('cl_outros')->nullable();
            $table->string('mensuracao_anterior')->nullable();
            $table->string('mensuracao_atual')->nullable();
            $table->boolean('coloracao_vermelho')->nullable();
            $table->boolean('clr_vermelho_palido')->nullable();
            $table->boolean('clr_vermelho_vinhoso')->nullable();
            $table->string('clr_outro')->nullable();
            $table->boolean('pr_integra')->nullable();
            $table->boolean('pr_edema')->nullable();
            $table->boolean('pr_eritema')->nullable();
            $table->boolean('pr_endurecimento')->nullable();
            $table->boolean('pr_prurido')->nullable();
            $table->boolean('pr_descamacao')->nullable();
            $table->boolean('pr_hiperpigmentacao')->nullable();
            $table->boolean('pr_dermatites')->nullable();
            $table->string('pr_outros')->nullable();
            $table->boolean('el_liquida')->nullable();
            $table->boolean('el_pastosa')->nullable();
            $table->boolean('el_solida')->nullable();
            $table->string('el_outro')->nullable();
            $table->boolean('cmp_sangramento')->nullable();
            $table->boolean('cmp_isquemia')->nullable();
            $table->boolean('cmp_edema')->nullable();
            $table->boolean('cmp_retracao')->nullable();
            $table->boolean('cmp_estenose')->nullable();
            $table->boolean('cmp_prolapso')->nullable();
            $table->boolean('cmp_hernia')->nullable();
            $table->boolean('cmp_dermatite')->nullable();
            $table->boolean('cmp_varizes')->nullable();
            $table->boolean('cmp_fistula')->nullable();
            $table->boolean('cmp_deiscencia')->nullable();
            $table->string('cmp_outro')->nullable();
            $table->boolean('permanencia')->nullable();
            $table->boolean('orientacao_previa')->nullable();
            $table->string('opr_qual')->nullable();
            $table->string('tipo_bolsa')->nullable();
            $table->string('tipo_placa')->nullable();
            $table->string('acessorios')->nullable();
            $table->string('outro')->nullable();
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
        Schema::dropIfExists('protocolo_avaliacao_estoma');
    }
}
