<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacoteservicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacoteservicos', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('pacote_id')->nullable();
            $table->foreign('pacote_id')->references('id')->on('pacotes');
            $table->unsignedBigInteger('servico_id')->nullable();
            $table->foreign('servico_id')->references('id')->on('servicos');
            $table->float('valor')->nullable();
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
        Schema::dropIfExists('pacoteservicos');
    }
}
