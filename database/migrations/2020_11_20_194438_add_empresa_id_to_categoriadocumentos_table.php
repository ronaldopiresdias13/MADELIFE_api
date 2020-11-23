<?php

use App\Categoriadocumento;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmpresaIdToCategoriadocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categoriadocumentos', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->softDeletes();
        });

        $categoriadocumentos = Categoriadocumento::all();

        foreach ($categoriadocumentos as $key => $categoriadocumento) {
            if (!$categoriadocumento->ativo) {
                $categoriadocumento->delete();
            }
        }

        Schema::table('categoriadocumentos', function (Blueprint $table) {
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
        Schema::table('categoriadocumentos', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
            $table->boolean('ativo')->default(true)->after('categoria');
        });

        $categoriadocumentos = Categoriadocumento::withTrashed()->get();

        foreach ($categoriadocumentos as $key => $categoriadocumento) {
            if ($categoriadocumento->deleted_at != null) {
                $categoriadocumento->ativo = false;
                $categoriadocumento->save();
            }
        }

        Schema::table('categoriadocumentos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
