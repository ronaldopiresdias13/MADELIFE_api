<?php

use App\Models\Unidadefederativa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadesfederativasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidadesfederativas', function (Blueprint $table) {
            $table->id();
            $table->char('codigo', 2);
            $table->string('termo', 25);
            $table->char('sigla', 2);
        });

        $ufs = [
            [
                'codigo' => '11',
                'termo' => 'Rondônia',
                'sigla' => 'RO'
            ],
            [
                'codigo' => '12',
                'termo' => 'Acre',
                'sigla' => 'AC'
            ],
            [
                'codigo' => '13',
                'termo' => 'Amazonas',
                'sigla' => 'AM'
            ],
            [
                'codigo' => '14',
                'termo' => 'Roraima',
                'sigla' => 'RR'
            ],
            [
                'codigo' => '15',
                'termo' => 'Pará',
                'sigla' => 'PA'
            ],
            [
                'codigo' => '16',
                'termo' => 'Amapá',
                'sigla' => 'AP'
            ],
            [
                'codigo' => '17',
                'termo' => 'Tocantins',
                'sigla' => 'TO'
            ],
            [
                'codigo' => '21',
                'termo' => 'Maranhão',
                'sigla' => 'MA'
            ],
            [
                'codigo' => '22',
                'termo' => 'Piauí',
                'sigla' => 'PI'
            ],
            [
                'codigo' => '23',
                'termo' => 'Ceará',
                'sigla' => 'CE'
            ],
            [
                'codigo' => '24',
                'termo' => 'Rio Grande do Norte',
                'sigla' => 'RN'
            ],
            [
                'codigo' => '25',
                'termo' => 'Paraíba',
                'sigla' => 'PB'
            ],
            [
                'codigo' => '26',
                'termo' => 'Pernambuco',
                'sigla' => 'PE'
            ],
            [
                'codigo' => '27',
                'termo' => 'Alagoas',
                'sigla' => 'AL'
            ],
            [
                'codigo' => '28',
                'termo' => 'Sergipe',
                'sigla' => 'SE'
            ],
            [
                'codigo' => '29',
                'termo' => 'Bahia',
                'sigla' => 'BA'
            ],
            [
                'codigo' => '31',
                'termo' => 'Minas Gerais',
                'sigla' => 'MG'
            ],
            [
                'codigo' => '32',
                'termo' => 'Espírito Santo',
                'sigla' => 'ES'
            ],
            [
                'codigo' => '33',
                'termo' => 'Rio de Janeiro',
                'sigla' => 'RJ'
            ],
            [
                'codigo' => '35',
                'termo' => 'São Paulo',
                'sigla' => 'SP'
            ],
            [
                'codigo' => '41',
                'termo' => 'Paraná',
                'sigla' => 'PR'
            ],
            [
                'codigo' => '42',
                'termo' => 'Santa Catarina',
                'sigla' => 'SC'
            ],
            [
                'codigo' => '43',
                'termo' => 'Rio Grande do Sul',
                'sigla' => 'RS'
            ],
            [
                'codigo' => '50',
                'termo' => 'Mato Grosso do Sul',
                'sigla' => 'MS'
            ],
            [
                'codigo' => '51',
                'termo' => 'Mato Grosso',
                'sigla' => 'MT'
            ],
            [
                'codigo' => '52',
                'termo' => 'Goiás',
                'sigla' => 'GO'
            ],
            [
                'codigo' => '53',
                'termo' => 'Distrito Federal',
                'sigla' => 'DF'
            ],
            [
                'codigo' => '98',
                'termo' => 'Países Estrangeiros',
                'sigla' => 'EX'
            ]
        ];

        foreach ($ufs as $key => $item) {
            $uf = new Unidadefederativa();
            $uf->codigo = $item['codigo'];
            $uf->termo  = $item['termo'];
            $uf->sigla  = $item['sigla'];
            $uf->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidadesfederativas');
    }
}
