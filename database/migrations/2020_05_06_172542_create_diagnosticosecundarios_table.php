<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosticosecundariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosticosecundarios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('descricao')->nullable();
            $table->string('referencia')->nullable();
            $table->foreignId('pil')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('diagnosticosecundarios');
    }
}
