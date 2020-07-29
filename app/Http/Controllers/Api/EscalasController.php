<?php

namespace App\Http\Controllers\Api;

use App\Escala;
use App\Cuidado;
use App\CuidadoEscala;
use App\Relatorioescala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EscalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = Escala::where('ativo', true);

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                // if ($key == 0) {
                //     $itens = Escala::where(
                //         ($where['coluna']) ? $where['coluna'] : 'id',
                //         ($where['expressao']) ? $where['expressao'] : 'like',
                //         ($where['valor']) ? $where['valor'] : '%'
                //     );
                // } else {
                $itens->where(
                    ($where['coluna']) ? $where['coluna'] : 'id',
                    ($where['expressao']) ? $where['expressao'] : 'like',
                    ($where['valor']) ? $where['valor'] : '%'
                );
                // }
            }
            // } else {
            //     $itens = Escala::where('id', 'like', '%')->limit(5);
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna']) ? $order['coluna'] : 'id',
                    ($order['tipo']) ? $order['tipo'] : 'asc'
                );
            }
        }

        $itens = $itens->get();

        if ($request['adicionais']) {
            foreach ($itens as $key => $iten) {
                foreach ($request['adicionais'] as $key => $adicional) {
                    if (is_string($adicional)) {
                        $iten[$adicional];
                    } else {
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) {
                                if ($iten[0] == null) {
                                    $iten2 = $iten[$a];
                                } else {
                                    foreach ($iten as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            } else {
                                if ($iten2[0] == null) {
                                    $iten2 = $iten2[$a];
                                } else {
                                    foreach ($iten2 as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $itens;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $escala = Escala::create([
            'empresa_id'            => $request->empresa_id,
            'ordemservico_id'       => $request->ordemservico_id,
            'prestador_id'          => $request->prestador_id,
            'servico_id'            => $request->servico_id,
            'horaentrada'           => $request->horaentrada,
            'horasaida'             => $request->horasaida,
            'dataentrada'           => $request->dataentrada,
            'datasaida'             => $request->datasaida,
            'periodo'               => $request->periodo,
            'assinaturaprestador'   => $request->assinaturaprestador,
            'assinaturaresponsavel' => $request->assinaturaresponsavel,
            'observacao'            => $request->observacao,
            'status'                => $request->status,
            'folga'                 => $request->folga,
            'substituto'            => $request->substituto
        ]);

        foreach ($request->cuidados as $key => $cuidado) {
            $cuidado_escala = CuidadoEscala::create([
                'escala_id'  => $escala->id,
                'cuidado_id' => Cuidado::find($cuidado['cuidado']['id'])->id,
                'data'       => $cuidado['data'],
                'hora'       => $cuidado['hora'],
                'status'     => $cuidado['status'],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Escala $escala)
    {
        $iten = $escala;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    $iten[$adicional];
                } else {
                    $iten2 = $iten;
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            if ($iten[0] == null) {
                                $iten2 = $iten[$a];
                            } else {
                                foreach ($iten as $key => $i) {
                                    $i[$a];
                                }
                            }
                        } else {
                            if ($iten2[0] == null) {
                                $iten2 = $iten2[$a];
                            } else {
                                foreach ($iten2 as $key => $i) {
                                    $i[$a];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $iten;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Escala $escala)
    {
        $escala->empresa_id            = $request->empresa_id;
        $escala->ordemservico_id       = $request->ordemservico_id;
        $escala->prestador_id          = $request->prestador_id;
        $escala->servico_id            = $request->servico_id;
        $escala->horaentrada           = $request->horaentrada;
        $escala->horasaida             = $request->horasaida;
        $escala->dataentrada           = $request->dataentrada;
        $escala->datasaida             = $request->datasaida;
        $escala->periodo               = $request->periodo;
        $escala->assinaturaprestador   = $request->assinaturaprestador;
        $escala->assinaturaresponsavel = $request->assinaturaresponsavel;
        $escala->observacao            = $request->observacao;
        $escala->status                = $request->status;
        $escala->folga                 = $request->folga;
        $escala->substituto            = $request->substituto;
        $escala->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Escala $escala)
    {
        $escala->ativo = false;
        $escala->save();
    }
}
