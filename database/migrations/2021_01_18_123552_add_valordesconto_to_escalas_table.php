<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValordescontoToEscalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escalas', function (Blueprint $table) {
            $table->string('valordesconto')->nullable()->after('valoradicional');
            $table->string('motivodesconto')->nullable()->after('motivoadicional');
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
            $table->dropColumn('valordesconto');
            $table->dropColumn('motivodesconto');
        });
    }
}
