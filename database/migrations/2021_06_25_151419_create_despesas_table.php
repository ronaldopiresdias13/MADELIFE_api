<?php

use App\Models\Despesas;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->char('codigo', 2);
            $table->string('descricao', 50);
        });
        $despesas = [
            [
                'codigo' => '01',
                'descricao' => 'Gases medicinais',
                 
            ],
            [
                'codigo' => '02',
                'descricao' => 'Medicamentos',
                 
            ],
            [
                'codigo' => '03',
                'descricao' => 'Materiais',
                 
            ],
            [
                'codigo' => '05',
                'descricao' => 'Diárias',
                 
            ],
            [
                'codigo' => '07',
                'descricao' => 'Taxas e aluguéis',
                
            ],
            [
                'codigo' => '08',
                'descricao' => 'OPME',
                
            ],
        ];

        foreach ($despesas as $key => $item) {
            $despesas = new Despesas();
            $despesas->codigo = $item['codigo'];
            $despesas->descricao  = $item['descricao'];
            $despesas->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('despesas');
    }
}
