<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemtabelaprecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemtabelaprecos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('versaotabelapreco_id')->references('id')->on('versaotabelaprecos');
            $table->string('codigo');
            $table->string('tiss');
            $table->string('tuss');
            $table->string('nome');
            $table->float('preco');
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
        Schema::dropIfExists('itemtabelaprecos');
    }
}
