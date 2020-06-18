<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariomedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horariomedicamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transcricao_produto_id');
            $table->foreign('transcricao_produto_id')->references('id')->on('transcricao_produto')->onDelete('cascade');
            $table->string('horario')->nullable();
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
        Schema::dropIfExists('horariomedicamentos');
    }
}
