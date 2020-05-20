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
        $itens = null;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }
        
        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Horariotrabalho::where(
                        ($where['coluna'   ])? $where['coluna'   ] : 'id'  ,
                        ($where['expressao'])? $where['expressao'] : 'like',
                        ($where['valor'    ])? $where['valor'    ] : '%'
                    );
                } else {
                    $itens->where(
                        ($where['coluna'   ])? $where['coluna'   ] : 'id',
                        ($where['expressao'])? $where['expressao'] : 'like',
                        ($where['valor'    ])? $where['valor'    ] : '%'
                    );
                }
            }
        } else {
            $itens = Horariotrabalho::where('id', 'like', '%');
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna'])? $order['coluna'] : 'id',
                    ($order['tipo'  ])? $order['tipo'  ] : 'asc'
                );
            }
        }
        
        $itens = $itens->get();
        
        if ($request['adicionais']) {
            foreach ($itens as $key => $iten) {
                foreach ($request['adicionais'] as $key => $adic) {
                    if (is_string($adic)) {
                        $iten[$adic];
                    } else {
                        switch (count($adic)) {
                            case 1:
                                $iten[$adic[0]];
                                break;
                            
                            case 2:
                                $iten[$adic[0]][$adic[1]];
                                break;
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
        $horariotrabalho = new Horariotrabalho;
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
            foreach ($request['adicionais'] as $key => $adic) {
                if (is_string($adic)) {
                    $iten[$adic];
                } else {
                    switch (count($adic)) {
                        case 1:
                            $iten[$adic[0]];
                            break;
                        
                        case 2:
                            $iten[$adic[0]][$adic[1]];
                            break;
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
        $horariotrabalho->update($request-all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Horariotrabalho  $horariotrabalho
     * @return \Illuminate\Http\Response
     */
    public function destroy(Horariotrabalho $horariotrabalho)
    {
        $horariotrabalho->delete();
    }
}
