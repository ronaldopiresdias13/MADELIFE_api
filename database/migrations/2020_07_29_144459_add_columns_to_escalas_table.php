<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEscalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escalas', function (Blueprint $table) {
            $table->string('motivoadicional')->nullable()->after('substituto');
            $table->string('valoradicional')->nullable()->after('substituto');
            $table->string('valorhoranoturno')->nullable()->after('substituto');
            $table->string('valorhoradiurno')->nullable()->after('substituto');
            $table->string('tipo')->nullable()->after('substituto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('escalas', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('valorhoradiurno');
            $table->dropColumn('valorhoranoturno');
            $table->dropColumn('valoradicional');
            $table->dropColumn('motivoadicional');
        });
    }
}
