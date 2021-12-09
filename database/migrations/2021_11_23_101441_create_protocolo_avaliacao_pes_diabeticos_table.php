<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloAvaliacaoPesDiabeticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo_avaliacao_pes_diabeticos', function (Blueprint $table) {
            $table->id();
            $table->boolean('pe_esquerdo_calos1')->nullable();
            $table->boolean('pe_esquerdo_calos2')->nullable();
            $table->boolean('pe_esquerdo_calos3')->nullable();
            $table->boolean('pe_esquerdo_calos4')->nullable();
            $table->boolean('pe_esquerdo_ulcera1')->nullable();
            $table->boolean('pe_esquerdo_ulcera2')->nullable();
            $table->boolean('pe_esquerdo_ulcera3')->nullable();
            $table->boolean('pe_esquerdo_ulcera4')->nullable();
            $table->boolean('pe_esquerdo_preulcera1')->nullable();
            $table->boolean('pe_esquerdo_preulcera2')->nullable();
            $table->boolean('pe_esquerdo_preulcera3')->nullable();
            $table->boolean('pe_esquerdo_preulcera4')->nullable();
            $table->boolean('pe_direito_calos1')->nullable();
            $table->boolean('pe_direito_calos2')->nullable();
            $table->boolean('pe_direito_calos3')->nullable();
            $table->boolean('pe_direito_calos4')->nullable();
            $table->boolean('pe_direito_ulcera1')->nullable();
            $table->boolean('pe_direito_ulcera2')->nullable();
            $table->boolean('pe_direito_ulcera3')->nullable();
            $table->boolean('pe_direito_ulcera4')->nullable();
            $table->boolean('pe_direito_preulcera1')->nullable();
            $table->boolean('pe_direito_preulcera2')->nullable();
            $table->boolean('pe_direito_preulcera3')->nullable();
            $table->boolean('pe_direito_preulcera4')->nullable();
            $table->boolean('monofilamento')->nullable();
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
        Schema::dropIfExists('protocolo_avaliacao_pes_diabeticos');
    }
}
