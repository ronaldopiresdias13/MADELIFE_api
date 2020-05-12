<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidademedidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidademedidas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->string('sigla')->nullable();
            $table->string('grupo')->nullable();
            $table->boolean('padrao')->default(false);
            $table->boolean('status')->default(true);
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('unidademedidas');
    }
}
