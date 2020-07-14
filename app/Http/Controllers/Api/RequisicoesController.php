<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Requisicao;
use App\RequisicaoProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequisicoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Requisicao();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Requisicao::where(
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
            $itens = Requisicao::where('id', 'like', '%');
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
        DB::transaction(function () use ($request) {
            $requisicao = Requisicao::create([
                'empresa_id' => $request['empresa_id'],
                'pessoa_id'  => $request['pessoa_id'],
                'data'       => $request['data'],
                'observacao' => $request['observacao'],
                'status'     => $request['status'],
            ]);
            if ($request['produtos']) {
                foreach ($request['produtos'] as $key => $produto) {
                    $requisicao_produto = RequisicaoProduto::create([
                        'requisicao_id' => $requisicao->id,
                        'produto_id'    => $produto['pivot']['produto_id'],
                        'quantidade'    => $produto['pivot']['quantidade'],
                        'observacao'    => $produto['pivot']['observacao'],
                        'status'        => $produto['pivot']['status'],
                    ]);
                }
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Requisicao  $requisicao
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Requisicao $requisicao)
    {
        $iten = $requisicao;

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
     * @param  \App\Requisicao  $requisicao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requisicao $requisicao)
    {
        DB::transaction(function () use ($request, $requisicao) {
            $requisicao->update([
                'empresa_id' => $request['empresa_id'],
                'pessoa_id' => $request['pessoa_id'],
                'data' => $request['data'],
                'observacao' => $request['observacao'],
                'status' => $request['status'],
            ]);
            if ($request['produtos']) {
                foreach ($request['produtos'] as $key => $produto) {
                    $requisicao_produto = RequisicaoProduto::updateOrCreate(
                        [
                            'requisicao_id'   => $requisicao->id,
                            'produto_id' => $produto['pivot']['produto_id']
                        ],
                        [
                            'quantidade' => $produto['pivot']['quantidade'],
                            'observacao' => $produto['pivot']['observacao'],
                            'status' => $produto['pivot']['status']
                        ]
                    );
                }
            }
        });

        // return response()->json('Cliente atualizado com sucesso!', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Requisicao  $requisicao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requisicao $requisicao)
    {
        // $requisicao->delete();
    }
}
