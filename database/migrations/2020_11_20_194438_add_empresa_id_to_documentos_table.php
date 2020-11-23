<?php

use App\Documento;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmpresaIdToDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->softDeletes();
        });

        $documentos = Documento::all();

        foreach ($documentos as $key => $documento) {
            $documento->empresa_id = $documento->paciente->empresa_id;
            $documento->save();
            if (!$documento->ativo) {
                $documento->delete();
            }
        }

        Schema::table('documentos', function (Blueprint $table) {
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
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
            $table->boolean('ativo')->default(true)->after('observacao');
        });

        $documentos = Documento::withTrashed()->get();

        foreach ($documentos as $key => $documento) {
            if ($documento->deleted_at != null) {
                $documento->ativo = false;
                $documento->save();
            }
        }

        Schema::table('documentos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
