<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfissionalConveniosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profissional_convenios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profissional');
            $table->foreign('profissional')->references('id')->on('profissionais')->onDelete('cascade');
            $table->foreignId('convenio')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('profissional_convenios');
    }
}
