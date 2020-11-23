<?php

use App\Venda;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmpresaIdToVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->softDeletes();
        });

        $vendas = Venda::all();

        foreach ($vendas as $key => $venda) {
            $venda->empresa_id = $venda->orcamento->empresa_id;
            $venda->save();
            if (!$venda->ativo) {
                $venda->delete();
            }
        }

        Schema::table('vendas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
            $table->boolean('ativo')->default(true)->after('data');
        });

        $vendas = Venda::withTrashed()->get();

        foreach ($vendas as $key => $venda) {
            if ($venda->deleted_at != null) {
                $venda->ativo = false;
                $venda->save();
            }
        }

        Schema::table('vendas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
