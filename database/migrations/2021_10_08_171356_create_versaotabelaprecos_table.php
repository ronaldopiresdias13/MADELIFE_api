<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersaotabelaprecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versaotabelaprecos', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('tabelapreco_id')->references('id')->on('tabelaprecos');
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
        Schema::dropIfExists('versaotabelaprecos');
    }
}
