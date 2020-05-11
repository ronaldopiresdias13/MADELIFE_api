<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuidadoGrupocuidadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuidado_grupocuidado', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuidado_id');
            $table->foreign('cuidado_id')->references('id')->on('cuidados')->onDelete('cascade');
            $table->unsignedBigInteger('grupocuidado_id');
            $table->foreign('grupocuidado_id')->references('id')->on('grupocuidados')->onDelete('cascade');
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
        Schema::dropIfExists('cuidado_grupocuidado');
    }
}
