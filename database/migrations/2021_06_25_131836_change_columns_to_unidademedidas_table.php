<?php

use App\Models\Unidademedida;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsToUnidademedidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidademedidas', function (Blueprint $table) {
            $table->char('codigo', 3)->after('id')->nullable();

            $table->dropColumn('grupo');
            $table->dropColumn('padrao');
            $table->dropColumn('status');
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
            $table->dropColumn('ativo');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });

        $ums = [
            [
                'codigo' => '001',
                'sigla' => 'AMP',
                'descricao' => 'Ampola'
            ],
            [
                'codigo' => '002',
                'sigla' => 'BUI',
                'descricao' => 'Bilhões de Unidades Internacionais'
            ],
            [
                'codigo' => '003',
                'sigla' => 'BG',
                'descricao' => 'Bisnaga'
            ],
            [
                'codigo' => '004',
                'sigla' => 'BOLS',
                'descricao' => 'Bolsa'
            ],
            [
                'codigo' => '005',
                'sigla' => 'CX',
                'descricao' => 'Caixa'
            ],
            [
                'codigo' => '006',
                'sigla' => 'CAP',
                'descricao' => 'Cápsula'
            ],
            [
                'codigo' => '007',
                'sigla' => 'CARP',
                'descricao' => 'Carpule'
            ],
            [
                'codigo' => '008',
                'sigla' => 'COM',
                'descricao' => 'Comprimido'
            ],
            [
                'codigo' => '009',
                'sigla' => 'DOSE',
                'descricao' => 'Dose'
            ],
            [
                'codigo' => '010',
                'sigla' => 'DRG',
                'descricao' => 'Drágea'
            ],
            [
                'codigo' => '011',
                'sigla' => 'ENV',
                'descricao' => 'Envelope'
            ],
            [
                'codigo' => '012',
                'sigla' => 'FLAC',
                'descricao' => 'Flaconete'
            ],
            [
                'codigo' => '013',
                'sigla' => 'FR',
                'descricao' => 'Frasco'
            ],
            [
                'codigo' => '014',
                'sigla' => 'FA',
                'descricao' => 'Frasco Ampola'
            ],
            [
                'codigo' => '015',
                'sigla' => 'GAL',
                'descricao' => 'Galão'
            ],
            [
                'codigo' => '016',
                'sigla' => 'GLOB',
                'descricao' => 'Glóbulo'
            ],
            [
                'codigo' => '017',
                'sigla' => 'GTS',
                'descricao' => 'Gotas'
            ],
            [
                'codigo' => '018',
                'sigla' => 'G',
                'descricao' => 'Grama'
            ],
            [
                'codigo' => '019',
                'sigla' => 'L',
                'descricao' => 'Litro'
            ],
            [
                'codigo' => '020',
                'sigla' => 'MCG',
                'descricao' => 'Microgramas'
            ],
            [
                'codigo' => '021',
                'sigla' => 'MUI',
                'descricao' => 'Milhões de Unidades Internacionais'
            ],
            [
                'codigo' => '022',
                'sigla' => 'MG',
                'descricao' => 'Miligrama'
            ],
            [
                'codigo' => '023',
                'sigla' => 'ML',
                'descricao' => 'Mililitro'
            ],
            [
                'codigo' => '024',
                'sigla' => 'OVL',
                'descricao' => 'Óvulo'
            ],
            [
                'codigo' => '025',
                'sigla' => 'PAS',
                'descricao' => 'Pastilha'
            ],
            [
                'codigo' => '026',
                'sigla' => 'LT',
                'descricao' => 'Lata'
            ],
            [
                'codigo' => '027',
                'sigla' => 'PER',
                'descricao' => 'Pérola'
            ],
            [
                'codigo' => '028',
                'sigla' => 'PIL',
                'descricao' => 'Pílula'
            ],
            [
                'codigo' => '029',
                'sigla' => 'PT',
                'descricao' => 'Pote'
            ],
            [
                'codigo' => '030',
                'sigla' => 'KG',
                'descricao' => 'Quilograma'
            ],
            [
                'codigo' => '031',
                'sigla' => 'SER',
                'descricao' => 'Seringa'
            ],
            [
                'codigo' => '032',
                'sigla' => 'SUP',
                'descricao' => 'Supositório'
            ],
            [
                'codigo' => '033',
                'sigla' => 'TABLE',
                'descricao' => 'Tablete'
            ],
            [
                'codigo' => '034',
                'sigla' => 'TUB',
                'descricao' => 'Tubete'
            ],
            [
                'codigo' => '035',
                'sigla' => 'TB',
                'descricao' => 'Tubo'
            ],
            [
                'codigo' => '036',
                'sigla' => 'UN',
                'descricao' => 'Unidade'
            ],
            [
                'codigo' => '037',
                'sigla' => 'UI',
                'descricao' => 'Unidade Internacional'
            ],
            [
                'codigo' => '038',
                'sigla' => 'CM',
                'descricao' => 'Centímetro'
            ],
            [
                'codigo' => '039',
                'sigla' => 'CONJ',
                'descricao' => 'Conjunto'
            ],
            [
                'codigo' => '040',
                'sigla' => 'KIT',
                'descricao' => 'Kit'
            ],
            [
                'codigo' => '041',
                'sigla' => 'MÇ',
                'descricao' => 'Maço'
            ],
            [
                'codigo' => '042',
                'sigla' => 'M',
                'descricao' => 'Metro'
            ],
            [
                'codigo' => '043',
                'sigla' => 'PC',
                'descricao' => 'Pacote'
            ],
            [
                'codigo' => '044',
                'sigla' => 'PÇ',
                'descricao' => 'Peça'
            ],
            [
                'codigo' => '045',
                'sigla' => 'RL',
                'descricao' => 'Rolo'
            ],
            [
                'codigo' => '046',
                'sigla' => 'GY',
                'descricao' => 'Gray'
            ],
            [
                'codigo' => '047',
                'sigla' => 'CGY',
                'descricao' => 'Centgray'
            ],
            [
                'codigo' => '048',
                'sigla' => 'PAR',
                'descricao' => 'Par'
            ],
            [
                'codigo' => '049',
                'sigla' => 'ADES',
                'descricao' => 'Adesivo Transdérmico'
            ],
            [
                'codigo' => '050',
                'sigla' => 'COM',
                'descricao' => 'EFEV Comprimido Efervescente'
            ],
            [
                'codigo' => '051',
                'sigla' => 'COM',
                'descricao' => 'MST Comprimido Mastigável'
            ],
            [
                'codigo' => '052',
                'sigla' => 'SACHE',
                'descricao' => 'Sache'
            ],
        ];

        foreach ($ums as $key => $item) {
            $um = new Unidademedida();
            $um->codigo = $item['codigo'];
            $um->descricao = $item['descricao'];
            $um->sigla  = $item['sigla'];
            $um->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unidademedidas', function (Blueprint $table) {
            $table->dropColumn('codigo');

            $table->string('grupo')->nullable();
            $table->boolean('padrao')->default(true);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }
}
