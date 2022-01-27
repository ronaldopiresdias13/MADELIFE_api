<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GrupoPrescricaoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos_prescricoes_a', function (Blueprint $table) {
            $table->foreignUuid('grupo_id')->references('id')->on('grupos_a')->onDelete('cascade');
            $table->foreignUuid('prescricao_id')->references('id')->on('prescricoes_a')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupos_prescricoes_a');
    }
}
