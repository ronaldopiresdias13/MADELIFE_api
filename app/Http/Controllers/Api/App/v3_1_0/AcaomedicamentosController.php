<?php

namespace App\Http\Controllers\Api\App\v3_1_0;

use App\Models\Acaomedicamento;
use App\Http\Controllers\Controller;
use App\Models\Ocorrencia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcaomedicamentosController extends Controller
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
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $prestador = $user->pessoa->prestador->id;
            Acaomedicamento::create([
                'transcricao_produto_id' => $request['transcricao_produto_id'],
                'prestador_id' => $prestador,
                'data'         => $request['data'],
                'hora'         => $request['hora'],
                'observacao'   => $request['observacao'],
                'status'       => $request['status'],
                'escala_id'    => $request['escala_id'],
            ]);
            try {
                $ocorrencias = Ocorrencia::where('tipo', '=', 'Medicamento Atrasado')->whereHas('escalas', function ($q) use ($request) {
                    $q->where('escala_id', '=', $request['escala_id']);
                })->where('transcricao_produto_id', '=', $request['transcricao_produto_id'])->whereRaw('horario like \'%' . $request['hora'] . ':00\'')->get();
                foreach ($ocorrencias as $ocorrencia) {
                    if ($ocorrencia != null) {
                        $ocorrencia->fill(['situacao' => 'Resolvida', 'justificativa' => 'Medicamento realizado'])->save();
                        $ocorrencia->chamados()->update(['finalizado' => 1, 'justificativa' => 'Medicamento realizado']);
                    }
                }
            } catch (Exception $e) {
                Log::error($e);
            }
        });

        return response()->json([
            'toast' => [
                'text' => 'Salvo com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Acaomedicamento  $acaomedicamento
     * @return \Illuminate\Http\Response
     */
    public function show(Acaomedicamento $acaomedicamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Acaomedicamento  $acaomedicamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Acaomedicamento $acaomedicamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Acaomedicamento  $acaomedicamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Acaomedicamento $acaomedicamento)
    {
        //
    }
}
