<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AbmidDiagnosticoPrincipal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abmid_diagnosticos_principais', function (Blueprint $table) {
            $table->foreignUuid('abmid_id')->references('id')->on('planilhas_abmids')->onDelete('cascade');
            $table->foreignUuid('diagnostico_principal_id')->references('id')->on('diagnosticos_pil')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abmid_diagnosticos_principais');
    }
}
