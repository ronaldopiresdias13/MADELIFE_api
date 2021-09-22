<?php

namespace App\Imports;

use App\Models\BaseProfissionais;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BaseProfissionaisImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if ($key != 0) {
                BaseProfissionais::create([
                    'empresa_id'        => 1,
                    'name'              => $row[2],
                    'email'             => $row[3],
                    'active'            => $row[4] == 'Y',
                    // 'activation_code'   => $row[5],
                    // 'priv_admin'        => $row[6],
                    // 'crypto'            => $row[7],
                    'cep'               => $row[8],
                    // 'endereco'          => $row[9 ],
                    'latitude'          => $row[10],
                    'longitude'         => $row[11],
                    'logradouro'        => $row[12],
                    'numero'            => $row[13],
                    'complemento'       => $row[14],
                    'bairro'            => $row[15],
                    'cidade'            => $row[16],
                    'uf'                => $row[17],
                    'cpf'               => $row[18],
                    'rg'                => $row[19],
                    'pis'               => $row[20],
                    'coren'             => $row[21],
                    'ccm'               => $row[22],
                    'cnpj1'             => $row[23],
                    'tipo_inss'         => $row[24],
                    'funcao'            => $row[25],
                    'tel1'              => $row[26],
                    'tel2'              => $row[27],
                    'tel3'              => $row[28],
                    'tel4'              => $row[29],
                    'tel5'              => $row[30],
                    'tel6'              => $row[31],
                    'complexidade'      => $row[32],
                    'comp_grau'         => $row[33],
                    'disponib'          => $row[34],
                    'bloqueio'          => $row[35],
                    'bloqueio_obs'      => $row[36],
                    'bloqueio_tomador'  => $row[37],
                    'regiao'            => $row[38],
                    'obs'               => $row[39],
                    'banco'             => $row[40],
                    'agencia'           => $row[41],
                    'conta'             => $row[42],
                    'conta_digito'      => $row[43],
                    'tipo_conta'        => $row[44],
                    'conta_terceiro'    => $row[45] == 'Y',
                    'nome_terceiro'     => $row[46],
                    'cpf_terceiro'      => $row[47],
                    'obs1'              => $row[48],
                    'endereco_terceiro' => $row[49],
                    'numero_contrato'   => $row[50],
                    'dt_inclusao'       => $row[51],
                    'dt_nascimento'     => $row[52],
                    'quem_inclui'       => $row[53],
                    'foto'              => $row[54],
                    'documentos'        => $row[55],
                    'nome_documentos'   => $row[56],
                    'tam_documentos'    => $row[57],
                    'logo'              => $row[58],
                ]);
            }
        }
    }
}
