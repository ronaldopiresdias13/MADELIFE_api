<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReducaoAcrescimoToProdutoMedicaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produto_medicao', function (Blueprint $table) {
            $table->string('reducaoAcrescimo')->nullable()->after('valor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produto_medicao', function (Blueprint $table) {
            $table->dropColumn('reducaoAcrescimo');
        });
    }
}
