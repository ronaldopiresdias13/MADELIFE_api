<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustoIdToOrccustosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orccustos', function (Blueprint $table) {
            $table->uuid('custo_id')->nullable()->after('orc_id');
            $table->foreign('custo_id')->references('id')->on('custos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orccustos', function (Blueprint $table) {
            $table->dropForeign(['custo_id']);
            $table->dropColumn('custo_id');
        });
    }
}
