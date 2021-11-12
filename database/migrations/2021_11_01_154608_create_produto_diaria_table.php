<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoDiariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('produto_diaria', function (Blueprint $table) {
        //     $table->uuid('id')->primary();

        //     $table->foreignUuid('produto_id')->references('id')->on('produtos');
        //     $table->foreignUuid('diaria_id')->references('id')->on('diarias');

        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produto_diaria');
    }
}
