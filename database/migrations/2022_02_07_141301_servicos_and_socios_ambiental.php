<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ServicosAndSociosAmbiental extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicos_and_socios_ambiental', function (Blueprint $table) {
            $table->foreignUuid('servico_id')->references('id')->on('servicos_socio_ambiental')->onDelete('cascade');
            $table->foreignUuid('anexo_b_id')->references('id')->on('planilhas_anexo_b')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicos_and_socios_ambiental');
    }
}
