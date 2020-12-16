<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasubmenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresasubmenus', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('empresamenu_id');
            $table->foreign('empresamenu_id')->references('id')->on('empresamenus');
            $table->string('nome')->nullable();
            $table->string('icone')->nullable();
            $table->string('rota')->nullable();
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
        Schema::dropIfExists('empresasubmenus');
    }
}
