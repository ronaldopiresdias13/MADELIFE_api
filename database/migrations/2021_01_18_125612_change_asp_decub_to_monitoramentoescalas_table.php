<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAspDecubToMonitoramentoescalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoramentoescalas', function (Blueprint $table) {
            $table->boolean('asp')->default(false)->change();
            $table->boolean('decub')->default(false)->change();
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
            $table->string('asp')->change();
            $table->string('decub')->change();
        });
    }
}
