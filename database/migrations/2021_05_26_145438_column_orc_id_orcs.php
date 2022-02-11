<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ColumnOrcIdOrcs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcs', function (Blueprint $table) {
            $table->uuid('orc_id')->after('empresa_id')->nullable();
            $table->foreign('orc_id')->references('id')->on('orcs');
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
            $table->dropForeign(['orc_id']);
            $table->dropColumn('orc_id');
        });
    }
}
