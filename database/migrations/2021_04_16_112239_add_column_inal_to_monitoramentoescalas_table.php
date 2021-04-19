<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInalToMonitoramentoescalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoramentoescalas', function (Blueprint $table) {
            $table->boolean('inal')->nullable()->default(0)->after('asp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monitoramentoescalas', function (Blueprint $table) {
            $table->dropColumn('inal');
        });
    }
}
