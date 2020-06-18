<?php

namespace App\Http\Controllers\Api;

use App\Conta;
use App\Pagamento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Conta();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Conta::where(
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
            $itens = Conta::where('id', 'like', '%');
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
        $conta = Conta::create(
            [
                'empresa_id'         => 1,
                'tipopessoa'         => $request['tipopessoa'],
                'pessoa_id'          => $request['pessoa_id'],
                'natureza_id'        => $request['natureza_id'],
                'valortotalconta'    => $request['valortotalconta'],
                'tipoconta'          => $request['tipoconta'],
                'historico'          => $request['historico'],
                'status'             => $request['status'],
                'nfe'                => $request['nfe'],
                'quantidadeconta'    => $request['quantidadeconta'],
                'valorpago'          => $request['valorpago'],
                'tipocontapagamento' => $request['tipocontapagamento'],
                'datavencimento'     => $request['datavencimento'],
                'dataemissao'        => $request['dataemissao'],
            ]
        );
        foreach ($request['pagamentos'] as $key => $pagamento) {
            $conta_pagamento = Pagamento::create([
                'empresa_id'        => $pagamento['empresa_id'],
                'conta_id'          => $conta->id,
                'contasbancaria_id' => $pagamento['contasbancaria_id'],
                'numeroboleto'      => $pagamento['numeroboleto'],
                'formapagamento'    => $pagamento['formapagamento'],
                'datavencimento'    => $pagamento['datavencimento'],
                'datapagamento'     => $pagamento['datapagamento'],
                'valorconta'        => $pagamento['valorconta'],
                'status'            => $pagamento['status'],
                'tipopagamento'     => $pagamento['tipopagamento'],
                'valorpago'         => $pagamento['valorpago'],
                'pagamentoparcial'  => $pagamento['pagamentoparcial'],
                'observacao'        => $pagamento['observacao'],
                'anexo'             => $pagamento['anexo'],
                'numeroconta'       => $pagamento['numeroconta'],

            ]);
        }
        //  return $conta;
        // $pagamento = Pagamento::create(
        //     [
        //         'empresa_id' => 1,
        //         'conta_id'   => Conta::create(
        //             [
        //                 'empresa_id'         => 1,
        //                 'tipopessoa'         => $request['tipoPessoa'],
        //                 'pessoa_id'          => $request['pessoa'],
        //                 'natureza_id'        => $request['natureza'],
        //                 'valortotalconta'    => $request['valorConta'],
        //                 'tipoconta'          => $request['tipoConta'],
        //                 'historico'          => $request['historico'],
        //                 'status'             => $request['status'],
        //                 'nfe'                => $request['nfe'],
        //                 'quantidadeconta'    => $request['quantidadeParcela'],
        //                 'valorpago'          => $request['valorContaPago'],
        //                 'tipocontapagamento' => $request['tipoPagamento'],
        //                 'datavencimento'     => $request['dataVencimento'],
        //                 'dataemissao'        => $request['dataEmissao'],
        //             ]
        //         )->id,
        //         'contasbancaria_id' =>  $request['contaBancaria'],
        //         'numeroboleto'      =>  "",
        //         'formapagamento'    =>  $request['formaPagamento'],
        //         'datavencimento'    =>  $request['dataVencimento'],
        //         'datapagamento'     =>  $request['dataPagamento'],
        //         'valorconta'        =>  $request['valorConta'],
        //         'status'            =>  $request['status'],
        //         'tipopagamento'     =>  $request['tipoPagamento'],
        //         'valorpago'         =>  $request['valorContaPago'],
        //         'pagamentoparcial'  =>  0,
        //         'observacao'        =>  "",
        //         'anexo'             =>  "",
        //         'numeroconta'       =>  $request['numeroParcela'],
        //     ]
        // );
        // return $pagamento;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Conta  $conta
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Conta $conta)
    {
        $iten = $conta;

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
     * @param  \App\Conta  $conta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conta $conta)
    {
        $conta->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Conta  $conta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conta $conta)
    {
        $conta->delete();
    }
}
