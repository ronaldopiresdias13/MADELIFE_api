<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdemservicoPrestadorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordemservico_prestadors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prestador_id');
            $table->foreign('prestador_id')->references('id')->on('prestadores')->onDelete('cascade');
            $table->unsignedBigInteger('ordemservico_id');
            $table->foreign('ordemservico_id')->references('id')->on('ordemservicos')->onDelete('cascade');
            $table->boolean('ativo')->default(true);
            $table->index('ativo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordemservico_prestadors');
    }
}
