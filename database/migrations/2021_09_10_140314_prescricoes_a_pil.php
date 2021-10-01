<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrescricoesAPil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescricoes_a_pil', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('pil_id')->references('id')->on('planilhas_pils')->onDelete('cascade');
            $table->unsignedBigInteger('cuidado_id');
            $table->foreign('cuidado_id')->references('id')->on('cuidados')->onDelete('cascade');

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
        Schema::dropIfExists('prescricoes_a_pil');
    }
}
