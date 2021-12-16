<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloAvaliacaoLesaoPressaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo_avaliacao_lesao_pressao', function (Blueprint $table) {
            $table->id();
            $table->boolean('braden_total_limitado')->nullable();
            $table->boolean('braden_muito_limitado')->nullable();
            $table->boolean('braden_levemente_limitado')->nullable();
            $table->boolean('braden_nenhuma_limitacao')->nullable();
            $table->boolean('braden_completamente_molhada')->nullable();
            $table->boolean('braden_muito_molhada')->nullable();
            $table->boolean('braden_ocasionalmente_molhada')->nullable();
            $table->boolean('braden_raramente_molhada')->nullable();
            $table->boolean('braden_acamado')->nullable();
            $table->boolean('braden_confinado_cadeira')->nullable();
            $table->boolean('braden_anda_ocasionalmente')->nullable();
            $table->boolean('braden_anda_frequentemente')->nullable();
            $table->boolean('braden_totalmente_imovel')->nullable();
            $table->boolean('braden_bastante_limitado')->nullable();
            $table->boolean('braden_levemente_limitado2')->nullable();
            $table->boolean('braden_nenhuma_limitacao2')->nullable();
            $table->boolean('braden_muito_pobre')->nullable();
            $table->boolean('braden_provavelmente_inadequado')->nullable();
            $table->boolean('braden_adequado')->nullable();
            $table->boolean('braden_excelente')->nullable();
            $table->boolean('braden_problema')->nullable();
            $table->boolean('braden_problema_potencial')->nullable();
            $table->boolean('braden_nenhum_problema')->nullable();
            $table->boolean('colchao_inadequado')->nullable();
            $table->boolean('colchao_agua')->nullable();
            $table->boolean('colchao_ar')->nullable();
            $table->boolean('colchao_piramidal')->nullable();
            $table->boolean('protetor')->nullable();
            $table->boolean('protetor_espuma')->nullable();
            $table->boolean('protetor_agua')->nullable();
            $table->boolean('protetor_ar')->nullable();
            $table->boolean('protetor_gel')->nullable();
            $table->boolean('posicionadores')->nullable();
            $table->string('posicionadores_regiao')->nullable();
            $table->boolean('banho_leito')->nullable();
            $table->boolean('banho_aspersao')->nullable();
            $table->string('banho_frequencia')->nullable();
            $table->string('higiene_intima')->nullable();
            $table->string('sabonetes')->nullable();
            $table->boolean('hidratantes')->nullable();
            $table->string('hidratantes_qual')->nullable();
            $table->string('cuidador_nome')->nullable();
            $table->string('cuidador_idade')->nullable();
            $table->string('cuidador_data_nasc')->nullable();
            $table->boolean('cuidador_disponibilidade')->nullable();
            $table->string('cuidador_observacoes')->nullable();
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
        Schema::dropIfExists('protocolo_avaliacao_lesao_pressao');
    }
}
