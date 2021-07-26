<?php

use App\Models\Pagamentoexterno;
use App\Models\Pagamentopessoa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateValorTaxasPagamentoPessoas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

        $taxas = Pagamentopessoa::all();

        foreach ($taxas as $key => $taxa) {
           
            $taxa->valorinss = 0;
            $taxa->valoriss = 0;
            $taxa->adicionalextra = 0;
            $taxa->adicionaloutros = 0;
            $taxa->taxaadm = 0;
            $taxa->tipovalorinss = 'R$';
            $taxa->tipovaloriss  = 'R$';
            $taxa->tipotaxaadm   = 'R$';
            $taxa->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
