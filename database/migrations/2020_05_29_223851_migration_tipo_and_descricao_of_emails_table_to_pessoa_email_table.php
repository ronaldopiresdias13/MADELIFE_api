<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationTipoAndDescricaoOfEmailsTableToPessoaEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $pessoas = App\Pessoa::all();
        foreach ($pessoas as $key => $pessoa) {
            $emails = $pessoa->emails;
            foreach ($emails as $key => $email) {
                App\PessoaEmail::where('email_id', $email->id)->update(['tipo' => $email->tipo, 'descricao' => $email->descricao]);
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
