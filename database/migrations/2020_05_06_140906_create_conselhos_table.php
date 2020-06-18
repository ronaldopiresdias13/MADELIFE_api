<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConselhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conselhos', function (Blueprint $table) {
            $table->id();
            $table->string('instituicao')->nullable();
            $table->string('uf')->nullable();
            $table->string('numero')->nullable();
            $table->foreignId('pessoa_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('conselhos');
    }
}
