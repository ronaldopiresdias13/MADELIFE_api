<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MensagemChamado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensagens_chamado', function (Blueprint $table) {
            $table->id();
            

            $table->unsignedBigInteger('prestador_id')->nullable();
            $table->foreign('prestador_id')->references('id')->on('pessoas')->onDelete('cascade');
           
            $table->unsignedBigInteger('atendente_id')->nullable();
            $table->foreign('atendente_id')->references('id')->on('pessoas')->onDelete('cascade');

            $table->text('message')->nullable();
            $table->text('uuid');

            $table->boolean('visto')->default(false);
            $table->unsignedBigInteger('chamado_id');
            $table->foreign('chamado_id')->references('id')->on('chamados')->onDelete('cascade');
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
        Schema::dropIfExists('mensagens_chamado');
    }
}
