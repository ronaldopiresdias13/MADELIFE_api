<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustoDiariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custo_diaria', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('custo_id')->references('id')->on('custos');
            $table->foreignUuid('diaria_id')->references('id')->on('diarias');
            $table->float('quantidade');
            $table->float('custounitario');
            $table->float('precounitario');
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
        Schema::dropIfExists('custo_diaria');
    }
}
