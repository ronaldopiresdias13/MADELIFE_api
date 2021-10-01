<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AbmidsDiagnosticosSecundarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abmids_ds', function (Blueprint $table) {
            $table->foreignUuid('abmid_id')->references('id')->on('planilhas_abmids')->onDelete('cascade');
            $table->foreignUuid('diagnostico_secundario_id')->references('id')->on('diagnosticos_pil')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abmids_ds');
    }
}
