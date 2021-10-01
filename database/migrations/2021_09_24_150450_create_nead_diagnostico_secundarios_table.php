<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNeadDiagnosticoSecundariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neads_diagnosticos_secundarios', function (Blueprint $table) {
            $table->foreignUuid('nead_id')->references('id')->on('neads')->onDelete('cascade');
            $table->foreignUuid('diagnostico_secundario_id')->references('id')->on('diagnosticos_pil')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('neads_diagnosticos_secundarios');
    }
}
