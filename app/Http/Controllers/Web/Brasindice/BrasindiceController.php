<?php

namespace App\Http\Controllers\Web\Brasindice;

use App\Http\Controllers\Controller;
use App\Models\Brasindice;
use App\Models\ItensBrasindice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrasindiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Brasindice::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        DB::transaction(function () use ($request, $empresa_id) {
            // Brasindice::create($request->all());
            $brasindice = Brasindice::create([
                'empresa_id' => $empresa_id,
                'descricao' => $request['descricao'],
                'versao_revista' => $request['revista'],
                'data' => $request['datarevista'],
                'estado' => $request['uf']
                // 'tipo_produto' => $request['tipo_produto'],
            ])->id;
            foreach ($request['itens_brasindice'] as $key => $itens_brasindice) {
                ItensBrasindice::create([
                    'brasindice_id' => $brasindice,
                    'tipo_produto' => $request['categoria'],
                    'cod_laboratorio' => $itens_brasindice['codigo_laboratorio'],
                    'nome_laboratorio' => $itens_brasindice['nome_laboratorio'],
                    'cod_produto' => $itens_brasindice['codigo_produto'],
                    'nome_produto' => $itens_brasindice['nome_produto'],
                    'cod_apresentacao' => $itens_brasindice['codigo_apresentacao'],
                    'nome_apresentacao' => $itens_brasindice['nome_apresentacao'],
                    'preco_produto' => $itens_brasindice['preco_produto'],
                    'quant_fracionado' => $itens_brasindice['quantidade_fracionamento'],
                    'tipo_preco' => $itens_brasindice['tipo_preco'],
                    'valor_fracionado_prod' => $itens_brasindice['valor_fracionado_produto'],
                    'ultima_edicao' => $itens_brasindice['ultima_edicao'],
                    'ipi_produto' => $itens_brasindice['ipi_produto'],
                    'flag_portaria' => $itens_brasindice['flag_portaria'],
                    'cod_ean' => $itens_brasindice['codigo_ean'],
                    'generico' => $itens_brasindice['generico'],
                    'cod_tuss' => $itens_brasindice['codigo_tuss'],
                    'status' => $itens_brasindice['status'],
                    'cod_tiss' => $itens_brasindice['codigo_tiss']

                ]);
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brasindice  $brasindice
     * @return \Illuminate\Http\Response
     */
    public function show(Brasindice $brasindice)
    {
        return $brasindice;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brasindice  $brasindice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brasindice $brasindice)
    {
        DB::transaction(function () use ($request, $brasindice) {
            $brasindice->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brasindice  $brasindice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brasindice $brasindice)
    {
        DB::transaction(function () use ($brasindice) {
            $brasindice->delete();
        });
    }
}
