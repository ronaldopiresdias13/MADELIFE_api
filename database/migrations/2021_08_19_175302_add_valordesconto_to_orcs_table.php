<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValordescontoToOrcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcs', function (Blueprint $table) {
            $table->float('valordesconto')->default(0)->after('valortotalservico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcs', function (Blueprint $table) {
            $table->dropColumn('valordesconto');
        });
    }
}
