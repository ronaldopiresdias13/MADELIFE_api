<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscalaComaGlasgowAnexoASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escalas_cglasgow_aa', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('anexo_a_id')->references('id')->on('planilhas_anexo_a')->onDelete('cascade');

            $table->string('categoria');
            $table->string('value');
            $table->integer('pontuacao');

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
        Schema::dropIfExists('escalas_cglasgow_aa');
    }
}
