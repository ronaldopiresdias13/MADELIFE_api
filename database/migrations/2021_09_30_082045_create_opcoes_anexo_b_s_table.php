<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcoesAnexoBSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opcoes_anexo_b', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('anexo_b_id')->references('id')->on('planilhas_anexo_b')->onDelete('cascade');

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
        Schema::dropIfExists('opcoes_anexo_b');
    }
}
