<?php

use App\Monitoramentoescala;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeleteToMonitoramentoescalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoramentoescalas', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->softDeletes();
        });

        $monitoramentoescalas = Monitoramentoescala::all();

        foreach ($monitoramentoescalas as $key => $monitoramentoescala) {
            $monitoramentoescala->empresa_id = $monitoramentoescala->escala->empresa_id;
            $monitoramentoescala->save();
            if (!$monitoramentoescala->ativo) {
                $monitoramentoescala->delete();
            }
        }

        Schema::table('monitoramentoescalas', function (Blueprint $table) {
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
        Schema::table('monitoramentoescalas', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
            $table->boolean('ativo')->default(true)->after('observacao');
        });

        $monitoramentoescalas = Monitoramentoescala::withTrashed()->get();

        foreach ($monitoramentoescalas as $key => $monitoramentoescala) {
            if ($monitoramentoescala->deleted_at != null) {
                $monitoramentoescala->ativo = false;
                $monitoramentoescala->save();
            }
        }

        Schema::table('monitoramentoescalas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
