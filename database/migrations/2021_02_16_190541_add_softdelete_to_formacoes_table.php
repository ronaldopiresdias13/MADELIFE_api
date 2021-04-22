<?php

use App\Models\Formacao;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeleteToFormacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formacoes', function (Blueprint $table) {
            $table->softDeletes();
        });

        $formacoes = Formacao::all();

        foreach ($formacoes as $key => $formacao) {
            if (!$formacao->ativo) {
                $formacao->delete();
            }
        }

        Schema::table('formacoes', function (Blueprint $table) {
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
        Schema::table('formacoes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });

        $formacoes = Formacao::withTrashed()->get();

        foreach ($formacoes as $key => $formacao) {
            if ($formacao->deleted_at != null) {
                $formacao->ativo = false;
                $formacao->save();
            }
        }

        Schema::table('formacoes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
