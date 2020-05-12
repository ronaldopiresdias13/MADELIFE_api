<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNaturezasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('naturezas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->string('tipo');
            $table->boolean('status');
            $table->unsignedBigInteger('categorianatureza_id');
            $table->foreign('categorianatureza_id')->references('id')->on('categorianaturezas')->onDelete('cascade');
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
        Schema::dropIfExists('naturezas');
    }
}
