<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfissionalConvenioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profissional_convenio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profissional_id')->nullable();
            $table->foreign('profissional_id')->references('id')->on('profissionais')->onDelete('cascade');
            $table->foreignId('convenio_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('profissional_convenio');
    }
}
