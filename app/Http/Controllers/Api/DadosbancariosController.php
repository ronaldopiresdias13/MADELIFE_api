<?php

namespace App\Http\Controllers\Api;

use App\Banco;
use App\Pessoa;
use App\Dadosbancario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DadosbancariosController extends Controller
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
                    $itens = Dadosbancario::where(
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
            $itens = Dadosbancario::where('id', 'like', '%');
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
                    $iten[$adic];
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
        $dadosbancario = new Dadosbancario;
        // $dadosbancario->pessoa = Pessoa::firstWhere('cpfcnpj', $request->pessoa)->id;
        // $dadosbancario->banco = Banco::firstWhere('codigo', $request->banco)->id;
        $dadosbancario->banco = $request->banco;
        $dadosbancario->pessoa = $request->pessoa;
        $dadosbancario->agencia = $request->agencia;
        $dadosbancario->conta = $request->conta;
        $dadosbancario->digito = $request->digito;
        $dadosbancario->tipoconta = $request->tipoconta;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Dadosbancario $dadosbancario)
    {
        $iten = $dadosbancario;

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
     * @param  \App\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dadosbancario $dadosbancario)
    {
        $dadosbancario->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dadosbancario $dadosbancario)
    {
        $dadosbancario->delete();
    }
}
