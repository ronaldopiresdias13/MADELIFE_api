<?php

use App\Models\Pessoa;
use App\Models\Tipopessoa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateTipoOfPessoasToTipopessoasTable extends Migration
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
            if ($pessoa->tipo == 'profissional') {
                $pessoa->tipo = 'Profissional';
            }
            Tipopessoa::firstOrCreate([
                'tipo'      => $pessoa->tipo,
                'pessoa_id' => $pessoa->id
            ]);
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
