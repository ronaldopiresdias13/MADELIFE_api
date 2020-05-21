<?php

namespace App\Http\Controllers\Api;

use App\Dadoscontratuais;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DadoscontratuaisController extends Controller
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
                    $itens = Dadoscontratuais::where(
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
            $itens = Dadoscontratuais::where('id', 'like', '%');
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
                foreach ($request['adicionais'] as $key => $adicional) {
                    if (is_string($adicional)) {
                        $iten[$adicional];
                    } else {
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) {
                                if ($iten[0] == null) {
                                    $iten2 = $iten[$a];
                                }
                                else {
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
        $dadoscontratuais = new Dadoscontratuais;
        $dadoscontratuais->tiposalario = $request->tiposalario;
        $dadoscontratuais->salario = $request->salario;
        $dadoscontratuais->cargahoraria = $request->cargahoraria;
        $dadoscontratuais->insalubridade = $request->insalubridade;
        $dadoscontratuais->percentualinsalubridade = $request->percentualinsalubridade;
        $dadoscontratuais->admissao = $request->admissao;
        $dadoscontratuais->demissao = $request->demissao;
        $dadoscontratuais->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dadoscontratuais  $dadoscontratuais
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Dadoscontratuais $dadoscontratuais)
    {
        $iten = $dadoscontratuais;

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
     * @param  \App\Dadoscontratuais  $dadoscontratuais
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dadoscontratuais $dadoscontratuais)
    {
        $dadoscontratuais->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dadoscontratuais  $dadoscontratuais
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dadoscontratuais $dadoscontratuais)
    {
        $dadoscontratuais->delete();
    }
}
