<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orc_produto', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('orc_id')->nullable();
            $table->foreign('orc_id')->references('id')->on('orcs');
            $table->unsignedBigInteger('produto_id')->nullable();
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->integer('quantidade')->nullable();
            $table->float('valorunitario')->nullable();
            $table->float('subtotal')->nullable();
            $table->float('custo')->nullable();
            $table->float('subtotalcusto')->nullable();
            $table->float('valorresultadomensal')->nullable();
            $table->float('valorcustomensal')->nullable();
            $table->boolean('locacao')->nullable()->default(false);
            $table->float('descricao')->nullable();
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
        Schema::dropIfExists('orc_produto');
    }
}
