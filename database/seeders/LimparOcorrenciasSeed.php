<?php

namespace Database\Seeders;

use App\Models\Chamado;
use App\Models\Ocorrencia;
use Illuminate\Database\Seeder;

class LimparOcorrenciasSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ocorrencia::where('created_at','<','2021-09-01 00:00:00')->update(['situacao'=>'Resolvida','justificativa'=>'Resolvida']);
        Chamado::whereHas('ocorrencia',function($q){
            $q->where('created_at','<','2021-09-01 00:00:00');
        })->update(['finalizado' => 1, 'justificativa' => 'Resolvida']);
    }
}
