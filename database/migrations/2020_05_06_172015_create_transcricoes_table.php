<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranscricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transcricoes', function (Blueprint $table) {
            $table->id();
            $table->string('medico');
            $table->string('crm');
            $table->string('receita');
            $table->unsignedBigInteger('profissional');
            $table->foreign('profissional')->references('id')->on('profissionais')->onDelete('cascade');
            $table->foreignId('pil')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('transcricoes');
    }
}
