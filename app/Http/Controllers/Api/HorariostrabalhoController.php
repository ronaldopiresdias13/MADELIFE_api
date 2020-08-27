<?php

namespace App\Http\Controllers\Api;

use App\Horariotrabalho;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HorariostrabalhoController extends Controller
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
            $itens = Horariotrabalho::with($with)->where('ativo', true);
        } else {
            $itens = Horariotrabalho::where('ativo', true);
        }

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                // if ($key == 0) {
                //     $itens = Horariotrabalho::where(
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
            //     $itens = Horariotrabalho::where('id', 'like', '%');
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
        $horariotrabalho = new Horariotrabalho();
        $horariotrabalho->diasemana           = $request->diasemana;
        $horariotrabalho->horarioentrada      = $request->horarioentrada;
        $horariotrabalho->horariosaida        = $request->horariosaida;
        $horariotrabalho->profissionalinterno = $request->profissionalinterno;
        $horariotrabalho->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Horariotrabalho  $horariotrabalho
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Horariotrabalho $horariotrabalho)
    {
        $iten = $horariotrabalho;

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
     * @param  \App\Horariotrabalho  $horariotrabalho
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Horariotrabalho $horariotrabalho)
    {
        $horariotrabalho->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Horariotrabalho  $horariotrabalho
     * @return \Illuminate\Http\Response
     */
    public function destroy(Horariotrabalho $horariotrabalho)
    {
        $horariotrabalho->ativo = false;
        $horariotrabalho->save();
    }
}
