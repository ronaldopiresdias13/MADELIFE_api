<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Orcamento;
use App\Pessoa;
use App\Servico;
use App\Orcamentoservico;
use App\Orcamentoproduto;
use App\Orcamentocusto;
use App\Historicoorcamento;
use App\HistoricoOrcamentoCusto;
use App\HistoricoOrcamentoProduto;
use App\HistoricoOrcamentoServico;
use Illuminate\Http\Request;

class OrcamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd('teste');
        $orcamentos = Orcamento::all();
        foreach ($orcamentos as $key => $orcamento) {
            // $orcamento->historicos;
            foreach ($orcamento->historicos as $key => $historico) {
                $historico->orcamentoservicos;
                $historico->orcamentoprodutos;
            }
        }
        return $orcamentos;
        $itens = null;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }
        
        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Orcamento::where(
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
            $itens = Orcamento::where('id', 'like', '%');
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
        $orcamento = Orcamento::firstOrCreate([
            'numero'     => $request['numero'],
            'tipo'       => $request['tipo'],
            'cliente_id' => $request['cliente_id'],
            'empresa_id' => $request['empresa_id'],
            'data'       => $request['data'],
            'quantidade' => $request['quantidade'],
            'unidade'    => "Meses",
            'cidade_id'  => $request['cidade_id'],
            'processo'   => $request['processo'],
            'situacao'   => $request['situacao'],
            'descricao'  => $request['descricao'],
            'observacao' => $request['observacao'],
            'status'     => 1
        ]);
 
        foreach ($request->historicoOrcamento as $key => $value) {
            $historico = Historicoorcamento::firstOrCreate([
                'orcamento_id'      => $orcamento->id,
                'data'              => $value['data'],
                'valortotalservico' => $value['valortotalservico'],
                'valortotalproduto' => $value['valortotalproduto'],
                'valortotalcusto'   => 0,
            ]);
            foreach ($value['itensServicoOrcamento'] as $key => $itens_servico) {
                $orcamentoServico = Orcamentoservico::firstOrCreate([
                    'servico_id'           => $itens_servico['servico_id'],
                    'quantidade'	       => $itens_servico['quantidade'],
                    'basecobranca'	       => $itens_servico['basecobranca'],
                    'frequencia'	       => $itens_servico['frequencia'],
                    'valorunitario'	       => $itens_servico['valorunitario'],
                    'subtotal'	           => $itens_servico['subtotal'],
                    'custo'                => $itens_servico['custo'],
                    'subtotalcusto'    	   => $itens_servico['subtotalcusto'],
                    'valorresultadomensal' => $itens_servico['valorresultadomensal'],
                    'valorcustomensal'     => $itens_servico['valorcustomensal'],
                    'icms'	               => $itens_servico['icms'],
                    'iss'                  => $itens_servico['iss'],
                    'inss'                 => $itens_servico['inss']
                ]);
                $historicoOrcamentoServico = HistoricoOrcamentoServico::firstOrCreate([
                    'orcamentoservico_id'   => $orcamentoServico->id,
                    'historicoorcamento_id' => $historico->id
                ]);
            }
            foreach ($value['itensProdutoOrcamento'] as $key => $itens_produto) {
                $orcamentoProduto = Orcamentoproduto::firstOrCreate([
                    'produto_id'           => $itens_produto['produto_id'],
                    'quantidade'	       => $itens_produto['quantidade'],
                    'valorunitario'	       => $itens_produto['valorunitario'],
                    'subtotal'	           => $itens_produto['subtotal'],
                    'custo'                => $itens_produto['custo'],
                    'subtotalcusto'    	   => $itens_produto['subtotalcusto'],
                    'valorresultadomensal' => $itens_produto['valorresultadomensal'],
                    'valorcustomensal'     => $itens_produto['valorcustomensal']
                ]);
                $historicoOrcamentoProduto = HistoricoOrcamentoProduto::firstOrCreate([
                    'orcamentoproduto_id'   => $orcamentoProduto->id,
                    'historicoorcamento_id' => $historico->id
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Orcamento $orcamento)
    {
        $iten = $orcamento;

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
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orcamento $orcamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orcamento $orcamento)
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function migracao(Request $request)
    {
        // dd($request);
       
    }
}
