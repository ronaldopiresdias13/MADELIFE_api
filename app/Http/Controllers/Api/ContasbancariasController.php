<?php

namespace App\Http\Controllers\Api;

use App\Conta;
use App\Pagamento;
use App\Contasbancaria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContasbancariasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Contasbancaria();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Contasbancaria::where(
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
            $itens = Contasbancaria::where('id', 'like', '%');
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
        $conta_pagamento = Pagamento::create([
            'empresa_id'        => $request['empresa_id'],
            'conta_id'          => Conta::create(
                [
                    'empresa_id'         => $request['empresa_id'],
                    'tipopessoa'         => "Conta Bancária",
                    'pessoa_id'          => null,
                    'natureza_id'        => null,
                    'valortotalconta'    => $request['saldo'],
                    'tipoconta'          => "Receber",
                    'historico'          => "Saldo Inicial " . $request['descricao'],
                    'status'             => 1,
                    'nfe'                => null,
                    'quantidadeconta'    => 1,
                    'valorpago'          => $request['saldo'],
                    'tipocontapagamento' => "Saldo Inicial",
                    'datavencimento'     => $request['data'],
                    'dataemissao'        => $request['data'],
                ]
            )->id,
            'contasbancaria_id' => Contasbancaria::create([
                'empresa_id' => $request['empresa_id'],
                'banco_id'   => $request['banco_id'],
                'agencia'    => $request['agencia'],
                'conta'      => $request['conta'],
                'digito'     => $request['digito'],
                'tipo'       => $request['tipo'],
                'saldo'      => $request['saldo'],
                'descricao'  => $request['descricao'],
            ])->id,
            'numeroboleto'      => null,
            'formapagamento'    => "Dinheiro",
            'datavencimento'    => $request['data'],
            'datapagamento'     => $request['data'],
            'valorconta'        => $request['saldo'],
            'status'            => 1,
            'tipopagamento'     => "Saldo Inicial",
            'valorpago'         => $request['saldo'],
            'pagamentoparcial'  => 0,
            'observacao'        => $request['descricao'],
            'anexo'             => null,
            'numeroconta'       => 1,

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contasbancaria  $contasbancaria
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Contasbancaria $contasbancaria)
    {
        $iten = $contasbancaria;

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
     * @param  \App\Contasbancaria  $contasbancaria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contasbancaria $contasbancaria)
    {
        $contasbancaria->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contasbancaria  $contasbancaria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contasbancaria $contasbancaria)
    {
        //
    }
}
