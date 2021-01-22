<?php

namespace App\Http\Controllers\Api;

use App\Models\Grupocuidado;
use App\Models\Cuidado;
use App\Models\CuidadoGrupocuidado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupocuidadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $with = [];

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    array_push($with, $adicional);
                } else {
                    $filho = '';
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            $filho = $a;
                        } else {
                            $filho = $filho . '.' . $a;
                        }
                    }
                    array_push($with, $filho);
                }
            }
            $itens = Grupocuidado::with($with)->where('ativo', true);
        } else {
            $itens = Grupocuidado::where('ativo', true);
        }

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                $itens->where(
                    ($where['coluna']) ? $where['coluna'] : 'id',
                    ($where['expressao']) ? $where['expressao'] : 'like',
                    ($where['valor']) ? $where['valor'] : '%'
                );
            }
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
                                if ($iten2 != null) {
                                    if ($iten2->count() > 0) {
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
        $total = Grupocuidado::where('empresa_id', $request->empresa_id)->count();
        $grupocuidado = new Grupocuidado();
        $grupocuidado->descricao = $request->descricao;
        $grupocuidado->codigo = $total + 1;
        $grupocuidado->empresa_id = $request->empresa_id;
        $grupocuidado->status = $request->status;
        $grupocuidado->save();

        foreach ($request['cuidado'] as $key => $cuidado) {
            $cuidado = CuidadoGrupocuidado::firstOrCreate([
                'grupocuidado_id' => $grupocuidado->id,
                'cuidado_id'    => $cuidado['id']
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Grupocuidado  $grupocuidado
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Grupocuidado $grupocuidado)
    {
        $iten = $grupocuidado;

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
                            if ($iten2 != null) {
                                if ($iten2->count() > 0) {
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
        }

        return $iten;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Grupocuidado  $grupocuidado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grupocuidado $grupocuidado)
    {
        $grupocuidado->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Grupocuidado  $grupocuidado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grupocuidado $grupocuidado)
    {
        $grupocuidado->ativo = false;
        $grupocuidado->save();
    }
}
