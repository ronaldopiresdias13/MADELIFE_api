<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use Illuminate\Http\Request;

class PagamentosController extends Controller
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
            $itens = Pagamento::with($with)->where('ativo', true);
        } else {
            $itens = Pagamento::where('ativo', true);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Pagamento $pagamento)
    {
        $iten = $pagamento;

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
     * @param  \App\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pagamento $pagamento)
    {
        $pagamento->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pagamento $pagamento)
    {
        $pagamento->ativo = false;
        $pagamento->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filtro(Request $request)
    {
        $pagamentos = Pagamento::where('pagamentos.empresa_id', $request->empresa_id)
            ->join('contas', 'contas.id', '=', 'pagamentos.conta_id')
            ->join('pessoas', 'pessoas.id', '=', 'contas.pessoa_id')
            ->select(
                'pagamentos.*',
                'pessoas.nome',
                'contas.tipoconta',
                'contas.tipopessoa',
                'contas.natureza_id',
                'contas.historico',
                'contas.nfe',
                'contas.quantidadeconta',
            )->where('contas.tipoconta', $request->tipo)
            ->where('contas.ativo', true)
            ->where('pagamentos.ativo', true)
            ->get();
        return $pagamentos;
    }

    public function filtroPagamentoFinanceiro(Request $request)
    {
        $pagamento = Pagamento::where('pagamentos.empresa_id', $request->empresa_id)
            ->join('contas', 'contas.id', '=', 'pagamentos.conta_id')
            ->join('pessoas', 'pessoas.id', '=', 'contas.pessoa_id')
            ->join('naturezas', 'naturezas.id', '=', 'contas.natureza_id')
            // ->join('contasbancarias', 'contasbancarias.id', '=', 'contas.contasbancarias_id')
            ->select(
                'pagamentos.*',
                'pagamentos.status',
                'pessoas.nome',
                'contas.tipoconta',
                'contas.tipopessoa',
                'contas.natureza_id',
                'contas.historico',
                'contas.nfe',
                'contas.quantidadeconta',
                // 'contasbancarias.descricao',
                'naturezas.categorianatureza_id'
            )->where('contas.tipoconta', $request->tipo)
            ->where('contas.ativo', true)
            ->where('pagamentos.ativo', true)
            ->whereBetween('datapagamento', [$request->data_inicio_pag, $request->data_fim_pag])
            // ->whereBetween('datavencimento', [$request->data_ini, $request->data_fim])
            ->get();
        return $pagamento;
    }
}
