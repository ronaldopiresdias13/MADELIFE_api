<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosticoPrimariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosticos_pil', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('codigo')->nullable();
            $table->text('descricao')->nullable();
            $table->string('nome')->nullable();
            $table->text('referencias')->nullable();
            $table->string('flag')->nullable();

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
        Schema::dropIfExists('diagnosticos_pil');
    }
}
