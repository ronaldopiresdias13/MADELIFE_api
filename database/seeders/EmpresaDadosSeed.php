<?php

namespace Database\Seeders;

use App\Models\EmpresaDados;
use Illuminate\Database\Seeder;

class EmpresaDadosSeed extends Seeder
{
    protected $sicred = [
        'codigo' => '748',
        'agencia' => '00703',
        'digito_agencia' => '0',
        'conta' => '74437',
        'digito_conta' => '9',
        'convenio' => '279',
        'cnpj' => '12316361000120',

        'convenio_externo' => '279',
        'nome_empresa' => 'HOME CARE ENFERLIFE HOSPITALAR',

        'nome' => 'SICREDI'
    ];

    protected $santander = [
        'codigo' => '033',
        'agencia' => '03800',
        'conta' => '13001140',
        'digito_conta' => '9',
        'digito_agencia' => '0',
        'cnpj' => '12316361000120',

        'convenio' => '00333800008302521456',
        'convenio_externo' => '00333800004905562674',

        'nome' => 'BANCO SANTANDER',
        'nome_empresa' => 'HOME CARE ENFERLIFE HOSPITALAR',

    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresa=new EmpresaDados();
        $empresa->fill($this->santander);
        $empresa->fill(['empresa_id'=>2])->save();

        $empresa=new EmpresaDados();
        $empresa->fill($this->sicred);
        $empresa->fill(['empresa_id'=>2])->save();

    }
}
