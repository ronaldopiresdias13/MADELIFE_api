<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PilPrescricaoA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescricoes_a_pil', function (Blueprint $table) {
            $table->foreignUuid('prescricao_id')->references('id')->on('prescricoes_a')->onDelete('cascade');
            $table->dropForeign(['cuidado_id']);
            $table->dropColumn('cuidado_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prescricoes_a_pil');
    }
}
