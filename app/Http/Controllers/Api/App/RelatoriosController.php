<?php

namespace App\Http\Controllers\Api\App;

use App\Escala;
use App\Http\Controllers\Controller;
use App\Relatorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatoriosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRelatoriosByEscalaId(Escala $escala)
    {
        return Relatorio::where('escala_id', $escala['id'])
            ->where('ativo', true)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllRelatoriosByEscalaId(Escala $escala)
    {
        // return $escala->ordemservico_id;
        return DB::table('relatorios')
            ->join('escalas', 'escalas.id', '=', 'relatorios.escala_id')
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->select('relatorios.*')
            ->where('ordemservicos.id', $escala->ordemservico_id)
            // ->groupBy('relatorios.nome')
            ->orderBy('relatorios.data', 'desc')
            ->limit(20)
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
        return $request;
        // DB::transaction(function () use ($request){
        //     Relatorio::create(

        //     );
        // }

        // Relatorio::create($request->all());
        // return response()->json([
        //     'alert' => [
        //         'title' => 'Salvo!',
        //         'text' => $request['tipo'] . ' realizado com sucesso!'
        //     ]
        // ], 200)
        //     ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function show(Relatorio $relatorio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Relatorio $relatorio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Relatorio $relatorio)
    {
        $relatorio->ativo = false;
        $relatorio->save();
        return response()->json([
            'alert' => [
                'title' => 'Parabéns!',
                'text' => 'Excluído com sucesso!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }
}
