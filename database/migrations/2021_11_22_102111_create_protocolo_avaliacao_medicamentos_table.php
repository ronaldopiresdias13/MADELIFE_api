<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocoloAvaliacaoMedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo_avaliacao_medicamento', function (Blueprint $table) {
            $table->id();
            $table->string('medicamento')->nullable();
            $table->string('dosagem')->nullable();
            $table->string('frequencia')->nullable();
            $table->string('data_inicio')->nullable();
            $table->string('data_fim')->nullable();
            $table->string('data1')->nullable();
            $table->string('cm1')->nullable();
            $table->string('data2')->nullable();
            $table->string('cm2')->nullable();
            $table->string('data3')->nullable();
            $table->string('cm3')->nullable();
            $table->string('data4')->nullable();
            $table->string('cm4')->nullable();
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
        Schema::dropIfExists('protocolo_avaliacao_medicamento');
    }
}
