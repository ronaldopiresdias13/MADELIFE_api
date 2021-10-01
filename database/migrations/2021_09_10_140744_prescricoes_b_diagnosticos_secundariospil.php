<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrescricoesBDiagnosticosSecundariospil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescricoes_b_diag_sec_pil', function (Blueprint $table) {
            $table->id();
            // $table->foreignUuid('pil_id')->references('id')->on('planilhas_pils')->onDelete('cascade');
            $table->unsignedBigInteger('prescricao_id');
            $table->foreign('prescricao_id')->references('id')->on('prescricoes_b_pil')->onDelete('cascade');
            $table->foreignUuid('diagnostico_secundario_id')->references('id')->on('diagnosticos_pil')->onDelete('cascade');

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
        Schema::dropIfExists('prescricoes_b_diag_sec_pil');
    }
}
