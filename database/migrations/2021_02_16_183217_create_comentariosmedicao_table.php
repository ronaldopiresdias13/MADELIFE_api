<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComentariosmedicaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentariosmedicao', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('medicoes_id');
            $table->foreign('medicoes_id')->references('id')->on('medicoes')->onDelete('cascade');
            $table->unsignedBigInteger('pessoa_id');
            $table->foreign('pessoa_id')->references('id')->on('pessoas')->onDelete('cascade');
            $table->string('comentario')->nullable();
            $table->string('data')->nullable();
            $table->string('hora')->nullable();
            $table->string('situacao')->nullable();
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
        Schema::dropIfExists('comentariosmedicao');
    }
}
