<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresamenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresamenus', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('empresamodulo_id');
            $table->foreign('empresamodulo_id')->references('id')->on('empresamodulos');
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
        Schema::dropIfExists('empresamenus');
    }
}
