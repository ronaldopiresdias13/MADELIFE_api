<?php

namespace App\Http\Controllers\Api;

use App\Relatorio;
use App\Ordemservico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RelatoriosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Relatorio();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Relatorio::where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                } else {
                    $itens->where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                }
            }
        } else {
            $itens = Relatorio::where('id', 'like', '%');
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
        // $relatorio = new Relatorio;
        // $relatorio->escala_id = $request->escala_id;
        // $relatorio->datahora = $request->datahora;
        // $relatorio->quadro = $request->quadro;
        // $relatorio->tipo = $request->tipo;
        // $relatorio->texto = $request->texto;
        // $relatorio->save();
        Relatorio::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Relatorio $relatorio)
    {
        $iten = $relatorio;

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
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Relatorio $relatorio)
    {
        $relatorio->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Relatorio $relatorio)
    {
        // $relatorio->delete();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function relatoriosOfOrdemservico(Ordemservico $ordemservico)
    {
        $relatorios = DB::table('relatorios')
            ->join('escalas', 'escalas.id', '=', 'relatorios.escala_id')
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->select('relatorios.*')
            // ->groupBy('relatorios.nome')
            ->orderBy('relatorios.data', 'desc')
            ->limit(20)
            ->get();
        return $relatorios;
    }
}
