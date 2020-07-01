<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCotacaoProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cotacao_produto', function (Blueprint $table) {
            $table->string('prazoentrega')->nullable()->after('valortotal');
            $table->string('formapagamento')->nullable()->after('valortotal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cotacao_produto', function (Blueprint $table) {
            $table->dropColumn('formapagamento');
            $table->dropColumn('prazoentrega');
        });
    }
}
