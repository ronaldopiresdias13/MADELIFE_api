<?php

namespace App\Http\Controllers\Api\Web\GestaoOrcamentaria;

use App\Http\Controllers\Controller;
use App\Models\Pacote;
use App\Models\Pacoteproduto;
use App\Models\Pacoteservico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa->id;
        return Pacote::with(['produtos.produto', 'servicos.servico'])
            ->where('empresa_id', $empresa_id)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa->id;
        DB::transaction(function () use ($request, $empresa_id) {
            $pacote = Pacote::create(
                [
                    'empresa_id' => $empresa_id,
                    'descricao' => $request['descricao'],
                ]
            );

            if ($request->servicos) {
                foreach ($request->servicos as $key => $servico) {
                    Pacoteservico::create(
                        [
                            'id'          => $servico['id'],
                            'servico_id'  => $servico['servico']['id'],
                            'pacote_id'   => $pacote->id,
                            'valor'       => $servico['valor']
                        ]
                    );
                }
            }
            if ($request->produtos) {
                foreach ($request->produtos as $key => $produto) {
                    Pacoteproduto::create(
                        [
                            'id'          => $produto['id'],
                            'produto_id'  => $produto['produto']['id'],
                            'pacote_id' => $pacote->id,
                            'valor' => $produto['valor']
                        ]
                    );
                }
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pacote  $pacote
     * @return \Illuminate\Http\Response
     */
    public function show(Pacote $pacote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pacote  $pacote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pacote $pacote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pacote  $pacote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pacote $pacote)
    {
        //
    }
}
