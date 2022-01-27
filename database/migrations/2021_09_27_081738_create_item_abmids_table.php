<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemAbmidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens_abmids', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('abmid_id')->references('id')->on('planilhas_abmids')->onDelete('cascade');
    
            $table->text('descricao');
            $table->text('item');
            $table->integer('ponto');

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
        Schema::dropIfExists('itens_abmids');
    }
}
