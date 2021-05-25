<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('versoes', function (Blueprint $table) {
             $table->uuid('id')->primary();
             $table->string('aplicacao');
             $table->string('plataforma');
             $table->string('versao');
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
        Schema::dropIfExists('versoes');
    }
}
