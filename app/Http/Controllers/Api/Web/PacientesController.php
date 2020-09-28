<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Ordemservico;
use App\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listNomePacientes(Request $request)
    {
        $user = $request->user();
        $profissional = $user->pessoa->profissional;

        $pacientes = DB::table('ordemservicos')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'pacientes.id', '=', 'homecares.orcamento_id')
            ->join('pessoas', 'pessoas.id', '=', 'pacientes.pessoa_id')
            ->where('ordemservicos.ativo', true)
            ->where('ordemservicos.empresa_id', $profissional->empresa_id)
            ->select('ordemservicos.id as value', 'pessoas.nome as label')
            ->get();
        return $pacientes;

        // $escalas = Ordemservico::with([
        //     'orcamento' => function ($query) {
        //         $query->select('id', 'cliente_id');
        //         $query->with(['homecare' => function ($query) {
        //             $query->select('id', 'orcamento_id', 'paciente_id');
        //             $query->with(['paciente' => function ($query) {
        //                 $query->select('id', 'pessoa_id');
        //                 $query->with(['pessoa' => function ($query) {
        //                     $query->select('id', 'nome');
        //                 }]);
        //             }]);
        //         }]);
        //         $query->with(['cliente' => function ($query) {
        //             $query->select('id', 'pessoa_id');
        //             $query->with(['pessoa' => function ($query) {
        //                 $query->select('id', 'nome');
        //             }]);
        //         }]);
        //     }
        // ])
        //     ->where('empresa_id', $profissional->empresa_id)
        //     ->where('ativo', true)
        //     ->get(['id', 'orcamento_id']);

        // return $escalas;
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
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show(Paciente $paciente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paciente $paciente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paciente $paciente)
    {
        //
    }
}
