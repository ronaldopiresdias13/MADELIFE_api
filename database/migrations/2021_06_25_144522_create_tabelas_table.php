<?php

use App\Models\Tabelas;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabelas', function (Blueprint $table) {
            $table->id();
            $table->char('codigo', 2);
            $table->string('descricao', 100);
        });

        $tabelas = [
            [
                'codigo' => '00',
                'descricao' => 'Tabela própria das operadoras',
                 
            ],
            [
                'codigo' => '18',
                'descricao' => 'Diárias, taxas e gases medicinais',
                 
            ],
            [
                'codigo' => '19',
                'descricao' => 'Materiais e Órteses, Próteses e Materiais Especiais (OPME)',
                 
            ],
            [
                'codigo' => '20',
                'descricao' => 'Medicamentos',
                 
            ],
            [
                'codigo' => '22',
                'descricao' => 'Procedimentos e eventos em saúde',
                
            ],
            [
                'codigo' => '90',
                'descricao' => 'Tabela Própria Pacote Odontológico',
                
            ],
            [
                'codigo' => '98',
                'descricao' => 'Tabela Própria de Pacotes',
                 
            ]
        ];

        foreach ($tabelas as $key => $item) {
            $tabelas = new Tabelas();
            $tabelas->codigo = $item['codigo'];
            $tabelas->descricao  = $item['descricao'];
            $tabelas->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabelas');
    }
}
