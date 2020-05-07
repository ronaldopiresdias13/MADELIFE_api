<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfissionalFormacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profissional_formacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profissional');
            $table->foreign('profissional')->references('id')->on('profissionais')->onDelete('cascade');
            $table->unsignedBigInteger('formacao');
            $table->foreign('formacao')->references('id')->on('formacoes')->onDelete('cascade');
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
        Schema::dropIfExists('profissional_formacoes');
    }
}
