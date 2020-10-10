<?php

namespace App\Http\Controllers\Api;

use App\Estoque;
use App\Http\Controllers\Controller;
use App\Orcamento;
use App\OrcamentoProduto;
use App\Produto;
use App\Saida;
use App\SaidaProduto;
use App\Venda;
use App\VendaSaida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        DB::transaction(function () use ($request) {
            $orcamento = Orcamento::create([
                'empresa_id'        => $request['orcamento']['empresa_id'],
                'cliente_id'        => $request['orcamento']['cliente_id'],
                'numero'            => $request['orcamento']['numero'],
                'processo'          => $request['orcamento']['processo'],
                'cidade_id'         => $request['orcamento']['cidade']['id'],
                'tipo'              => $request['orcamento']['tipo'],
                'data'              => $request['orcamento']['data'],
                'unidade'           => $request['orcamento']['unidade'],
                'quantidade'        => $request['orcamento']['quantidade'],
                'situacao'          => $request['orcamento']['situacao'],
                'descricao'         => $request['orcamento']['descricao'],
                'valortotalservico' => $request['orcamento']['valortotalservico'],
                'valortotalcusto'   => $request['orcamento']['valortotalcusto'],
                'valortotalproduto' => $request['orcamento']['valortotalproduto'],
                'observacao'        => $request['orcamento']['observacao'],
                'status'            => 1
            ]);
            if ($request['orcamento']['produtos']) {
                foreach ($request['orcamento']['produtos'] as $key => $produto) {
                    $orcamento_produto = OrcamentoProduto::create(
                        [
                            'orcamento_id'         => $orcamento->id,
                            'produto_id'           => $produto['produto_id'],
                            'quantidade'           => $produto['quantidade'],
                            'valorunitario'        => $produto['valorunitario'],
                            'custo'                => $produto['custo'],
                            'subtotal'             => $produto['subtotal'],
                            'subtotalcusto'        => $produto['subtotalcusto'],
                            'valorresultadomensal' => $produto['valorresultadomensal'],
                            'valorcustomensal'     => $produto['valorcustomensal']
                        ]
                    );
                }
            }
            $saida = Saida::create([
                'empresa_id'      => $request['saida']['empresa_id'],
                'data'            => $request['saida']['data'],
                'descricao'       => $request['saida']['descricao'],
                'profissional_id' => $request['saida']['profissional_id']
            ]);
            if ($request['orcamento']['produtos']) {
                foreach ($request['orcamento']['produtos'] as $key => $produto) {
                    $prod = Produto::find($produto["produto_id"]);
                    if ($prod->controlelote) {
                        if ($produto['lote']) {
                            $estoque = Estoque::firstWhere('lote', $produto['lote']);
                            if ($estoque) {
                                $atualiza_quantidade_estoque = Estoque::firstWhere('lote', $produto['lote']);
                                $atualiza_quantidade_estoque->quantidade = $atualiza_quantidade_estoque->quantidade - $produto['quantidade'];
                                $atualiza_quantidade_estoque->update();
                            } else {
                                $nova_estoque = Estoque::create([
                                    'produto_id' => $produto['produto_id'],
                                    'unidade'    => $prod->unidademedida_id,
                                    'quantidade' => $produto['quantidade'],
                                    'lote'       => $produto['lote'],
                                    'validade'   => $produto['validade'],
                                    'ativo'      => 1
                                ]);
                            }
                            // return $estoque;
                        }
                    }
                    $saida_produto = SaidaProduto::create([
                        'saida_id'      => $saida->id,
                        'produto_id'    => $produto['produto_id'],
                        'quantidade'    => $produto['quantidade'],
                        'lote'          => $produto['lote'],
                        'valor'         => $produto['valor'],
                        'ativo'         => 1
                    ]);
                    $prod->quantidadeestoque = $prod->quantidadeestoque - $produto["quantidade"];
                    $prod->update();
                }
            }
            $venda_saida = VendaSaida::create([
                'venda_id' => Venda::create([
                    'orcamento_id' => $orcamento->id,
                    'realizada' => 1,
                    'data' => $request['saida']['data'],
                    'ativo' => 1
                ])->id,
                'saida_id' => $saida->id,
                'ativo' => 1
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function show(Venda $venda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venda $venda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venda $venda)
    {
        //
    }
}
