<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloMedicacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo_medicacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('protocolo_id');
            $table->foreign('protocolo_id')->references('id')->on('protocolo_skin')->onDelete('cascade');
            $table->unsignedBigInteger('protocolo_avaliacao_medicacao_id')->nullable();
            $table->foreign('protocolo_avaliacao_medicacao_id')->references('id')->on('protocolo_avaliacao_medicamento');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->boolean('curativo_cateter_picc')->nullable();
            $table->boolean('curativo_portacath')->nullable();
            $table->boolean('cateter_periferico')->nullable();
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
        Schema::dropIfExists('protocolo_medicacao');
    }
}
