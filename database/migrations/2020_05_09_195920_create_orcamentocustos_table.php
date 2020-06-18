<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcamentocustosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orcamentocustos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->float('quantidade');
            $table->string('unidade');
            $table->float('valorunitario');
            $table->float('valortotal');
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
        Schema::dropIfExists('orcamentocustos');
    }
}
