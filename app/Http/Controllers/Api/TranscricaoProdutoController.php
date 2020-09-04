<?php

namespace App\Http\Controllers\api;

use App\TranscricaoProduto;
use App\Http\Controllers\Controller;
use App\Transcricao;
use Illuminate\Http\Request;

class TranscricaoProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TranscricaoProduto  $transcricao_produto
     * @return \Illuminate\Http\Response
     */
    public function destroy(TranscricaoProduto $transcricao_produto)
    {
        $transcricao_produto->ativo = false;
        $transcricao_produto->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listaTranscricoes(Request $request)
    {
        // $user = $request->user();
        // $profissional = $user->pessoa->profissional;

        // $transcricoes = Transcricao::with([
        //     'ordemservico' => function ($query) {
        //         $query->select('id', 'orcamento_id');
        //         $query->with(['orcamento' => function ($query) {
        //             $query->select('id');
        //             $query->with(['homecare' => function ($query) {
        //                 $query->select('id', 'orcamento_id', 'paciente_id');
        //                 $query->with(['paciente' => function ($query) {
        //                     $query->select('id', 'pessoa_id');
        //                     $query->with(['pessoa' => function ($query) {
        //                         $query->select('id', 'nome');
        //                     }]);
        //                 }]);
        //             }]);
        //         }]);
        //     }
        // ])
        //     ->where('empresa_id', $profissional->empresa_id)
        //     ->where('ativo', true)
        //     ->get(['id', 'orcamento_id']);

        // return $transcricoes;
        return 'transcricoes';
    }
}
