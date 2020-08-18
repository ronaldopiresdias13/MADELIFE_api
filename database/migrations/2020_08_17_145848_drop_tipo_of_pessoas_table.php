<?php

use App\Pessoa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTipoOfPessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->string('tipo')->nullable()->after('nascimento');
        });
        $pessoas = Pessoa::all();
        foreach ($pessoas as $key => $pessoa) {
            foreach ($pessoa->tipopessoas as $key => $tipopessoa) {
                $pessoa->tipo = $tipopessoa['tipo'];
                $pessoa->save();
            }
        }
    }
}
