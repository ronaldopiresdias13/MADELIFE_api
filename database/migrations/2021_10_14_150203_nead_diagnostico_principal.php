<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NeadDiagnosticoPrincipal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nead_diagnosticos_principais', function (Blueprint $table) {
            $table->foreignUuid('nead_id')->references('id')->on('neads')->onDelete('cascade');
            $table->foreignUuid('diagnostico_principal_id')->references('id')->on('diagnosticos_pil')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nead_diagnosticos_principais');
    }
}
