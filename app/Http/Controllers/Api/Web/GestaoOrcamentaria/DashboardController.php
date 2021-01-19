<?php

namespace App\Http\Controllers\Api\Web\GestaoOrcamentaria;

use App\Http\Controllers\Controller;
use App\Ordemservico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function dashboarContratosPorPoriodo(Request $request)
    {
        // return $request->data_fim;
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return Ordemservico::where('ordemservicos.empresa_id', $empresa_id)
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->where('orcamentos.data', '<=', $request->data_fim)
            // ->where('orcamentos.tipo', 'Home Care')
            ->select(
                'ordemservicos.*',
                'orcamentos.cidade_id',
                'orcamentos.tipo as tipoorcamento',
                'orcamentos.data',
                'orcamentos.situacao as situacaoorcamento',
            )
            ->get();
    }
}
