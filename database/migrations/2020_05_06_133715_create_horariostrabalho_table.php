<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariostrabalhoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horariostrabalho', function (Blueprint $table) {
            $table->id();
            $table->string('diasemana')->nullable();
            $table->string('horarioentrada')->nullable();
            $table->string('horariosaida')->nullable();
            $table->unsignedBigInteger('profissional_id')->nullable();
            $table->foreign('profissional_id')->references('id')->on('profissionais')->onDelete('cascade');
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
        Schema::dropIfExists('horariostrabalho');
    }
}
