<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DiagnosticosSecundariosPil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosticos_secundarios_pil', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('pil_id')->references('id')->on('planilhas_pils')->onDelete('cascade');
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
        Schema::dropIfExists('diagnosticos_secundarios_pil');
    }
}
