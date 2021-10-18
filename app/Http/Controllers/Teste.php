<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {

        /* Teste Importação Brasindice */
        // $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        // $empresa_id = 1;

        // $file = fopen("/home/lucas/Área de Trabalho/Brasindice/MEDICAMENTO_BRA_PMC.txt", "r");

        // while (!feof($file)) {
        //     $linha = fgets($file);
        //     $array = explode('","', $linha);
        //     foreach ($array as $key => $item) {
        //         $array[$key] = str_replace(['"', "\r", "\n"], "", $item);
        //     }
        //     // <<<<<<< HEAD
        //     // dd($array);

        //     $produto = new Produto();
        //     $produto->empresa_id = $empresa_id;
        //     $produto->tabela = $request->tabela;
        //     $produto->tipoproduto_id = Tipoproduto::firstOrCreate(
        //         [
        //             "empresa_id" => $empresa_id,
        //             "descricao" => "MEDICAMENTOS"
        //         ]
        //     )->id;
        //     $produto->codigo = $array[0] . '.' . $array[2] . '.' . $array[4];
        //     $produto->codigoTabela = 20;
        //     $produto->codigoDespesa = 02;
        //     $produto->descricao = $array[3] . ' ' . $array[5];
        //     $produto->inidademedida_id = "????????????????????";
        //     $produto->codigobarra = $array[13];
        //     dd($produto);
        // }

        // fclose($file);

        // return 'ok';
    }
}
