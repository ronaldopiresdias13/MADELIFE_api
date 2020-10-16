<?php

use App\Pessoa;
use App\PessoaTelefone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationTipoAndDescricaoOfTelefonesTableToPessoaTelefoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $pessoas = Pessoa::all();
        foreach ($pessoas as $key => $pessoa) {
            $telefones = $pessoa->telefones;
            foreach ($telefones as $key => $telefone) {
                PessoaTelefone::where('telefone_id', $telefone->id)->update(['tipo' => $telefone->tipo, 'descricao' => $telefone->descricao]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
