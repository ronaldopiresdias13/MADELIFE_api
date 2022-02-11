<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OcorrenciaEmpresaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('ocorrencias', function (Blueprint $table) {
             $table->unsignedBigInteger('empresa_id')->nullable();
             $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ocorrencias');
    }
}
