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
            $table->char('codigobanco', 3);
            $table->char('loteservico', 4);
            $table->char('tiporegistro', 1);
            $table->char('filler', 9);
            $table->char('quantidadelotesarquivo', 6);
            $table->char('quantidaderegarquivo', 6);
            $table->char('filler2', 211);
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
