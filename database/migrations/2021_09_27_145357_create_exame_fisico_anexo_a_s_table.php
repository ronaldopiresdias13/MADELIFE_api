<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExameFisicoAnexoASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exame_fisico_anexo_a', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('anexo_a_id')->references('id')->on('planilhas_anexo_a')->onDelete('cascade');

            $table->string('categoria');
            $table->string('descricao_value_2')->nullable();
            $table->string('value')->nullable();
            $table->string('descricao_value')->nullable();



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
        Schema::dropIfExists('exame_fisico_anexo_a');
    }
}
