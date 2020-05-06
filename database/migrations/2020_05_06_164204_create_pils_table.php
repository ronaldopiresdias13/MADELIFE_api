<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('profissional');
            $table->foreign('profissional')->references('id')->on('profissionais')->onDelete('cascade');
            $table->string('diagnosticoprincipal');
            $table->string('data');
            $table->string('prognostico')->nullable();
            $table->string('avaliacao')->nullable();
            $table->string('revisao')->nullable();
            $table->string('evolucao')->nullable();
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
        Schema::dropIfExists('pils');
    }
}
