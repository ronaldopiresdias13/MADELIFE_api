<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrccustosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orccustos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('orc_id')->nullable();
            $table->foreign('orc_id')->references('id')->on('orcs');
            $table->string('descricao')->nullable();
            $table->float('quantidade')->nullable();
            $table->string('unidade')->nullable();
            $table->float('valorunitario')->nullable();
            $table->float('valortotal')->nullable();
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
        Schema::dropIfExists('orccustos');
    }
}
