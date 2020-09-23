<?php

namespace App\Http\Controllers\Api;

use App\Cuidado;
use App\Empresa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CuidadosController extends Controller
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
            $itens = Cuidado::with($with)->where('ativo', true);
        } else {
            $itens = Cuidado::where('ativo', true);
        }

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                // if ($key == 0) {
                //     $itens = Cuidado::where(
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
            //     $itens = Cuidado::where('id', 'like', '%');
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
        $cuidado = new Cuidado();
        $cuidado->descricao = $request->descricao;
        $cuidado->codigo = $request->codigo;
        $cuidado->empresa_id = $request->empresa_id;
        $cuidado->status = $request->status;
        $cuidado->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Cuidado $cuidado)
    {
        $iten = $cuidado;

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
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cuidado $cuidado)
    {
        $cuidado->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cuidado $cuidado)
    {
        $cuidado->ativo = false;
        $cuidado->save();
    }
    public function quantidadecuidados(Empresa $empresa)
    {
        return Cuidado::where('empresa_id', $empresa['id'])->where('ativo', 1)->count();
    }
    public function indexbyempresa(Empresa $empresa)
    {
        return Cuidado::Where('empresa_id', $empresa['id'])
            ->where('ativo', 1)
            ->get();
    }
}
