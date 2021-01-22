<?php

namespace App\Http\Controllers\Api;

use App\Models\Banco;
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
            $itens = Dadosbancario::with($with)->where('ativo', true);
        } else {
            $itens = Dadosbancario::where('ativo', true);
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
        $dadosbancario = new Dadosbancario();
        // $dadosbancario->pessoa = Pessoa::firstWhere('cpfcnpj', $request->pessoa)->id;
        // $dadosbancario->banco = Banco::firstWhere('codigo', $request->banco)->id;
        $dadosbancario->empresa_id = $request->empresa_id;
        $dadosbancario->banco_id = $request->banco_id;
        $dadosbancario->pessoa_id = $request->pessoa_id;
        $dadosbancario->agencia = $request->agencia;
        $dadosbancario->conta = $request->conta;
        $dadosbancario->digito = $request->digito;
        $dadosbancario->tipoconta = $request->tipoconta;
        $dadosbancario->ativo = 1;
        $dadosbancario->save();
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
     * @param  \App\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dadosbancario $dadosbancario)
    {
        $dadosbancario->banco_id = $request->banco_id;
        $dadosbancario->pessoa_id = $request->pessoa_id;
        $dadosbancario->agencia = $request->agencia;
        $dadosbancario->conta = $request->conta;
        $dadosbancario->digito = $request->digito;
        $dadosbancario->tipoconta = $request->tipoconta;
        $dadosbancario->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dadosbancario $dadosbancario)
    {
        $dadosbancario->ativo = false;
        $dadosbancario->save();
    }
}
