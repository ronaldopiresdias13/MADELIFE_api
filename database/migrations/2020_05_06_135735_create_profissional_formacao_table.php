<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfissionalFormacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profissional_formacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profissional_id')->nullable();
            $table->foreign('profissional_id')->references('id')->on('profissionais')->onDelete('cascade');
            $table->unsignedBigInteger('formacao_id')->nullable();
            $table->foreign('formacao_id')->references('id')->on('formacoes')->onDelete('cascade');
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
        Schema::dropIfExists('profissional_formacao');
    }
}
