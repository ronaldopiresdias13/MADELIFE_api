<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnabtrailerarquivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnabtrailerarquivos', function (Blueprint $table) {
            $table->id();
            $table->string('codigobanco')->nullable();
            $table->string('loteservico')->nullable();
            $table->string('tiporegistro')->nullable();
            $table->string('filler')->nullable();
            $table->string('quantidadelotesarquivo')->nullable();
            $table->string('quantidaderegarquivo')->nullable();
            $table->string('filler2')->nullable();
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
        Schema::dropIfExists('cnabtrailerarquivos');
    }
}
