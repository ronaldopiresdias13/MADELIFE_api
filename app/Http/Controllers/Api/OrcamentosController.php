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
            $itens = Orcamento::all();
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
        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function show(Orcamento $orcamento)
    {
        return $orcamento;
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
        $orcamento = Orcamento::firstOrCreate([
            'numero' => $request['numeroOrcamento'],
            'tipo' => $request['tipoOrcamento'],
            'cliente_id' => Pessoa::firstWhere('nome', $request['clienteId'])->clientes[0]->id,
            'empresa_id' => 1,
            'data' => $request['data'],
            'quantidade' => $request['cicloMeses'],
            'unidade' => "Meses",
            'cidade_id' => $request['cidade'],
            'processo' => $request['numeroProcesso'],
            'situacao' => $request['situacao'],
            'descricao' => $request['descricao'],
            'observacao' => $request['observacao'],
        ]);
 
        foreach ($request->historicoOrcamento as $key => $value) {
            $historico = Historicoorcamento::firstOrCreate([
                'orcamento_id'      => $orcamento->id,
                'data'              => $value['data'],
                'valortotalservico' => $value['valorTotalServico'],
                'valortotalproduto' => $value['valorTotalItensProduto'],
                'valortotalcusto'   => 0,
            ]);
            foreach ($value['itensServicoOrcamento'] as $key => $itens_servico) {
                $orcamentoServico = Orcamentoservico::firstOrCreate([
                    'servico_id'           => Servico::firstWhere('descricao', $itens_servico['servico']['descricao'])->id,
                    'quantidade'	       => $itens_servico['quantidade'],
                    'basecobranca'	       => $itens_servico['baseCobranca'],
                    'frequencia'	       => $itens_servico['frequencia'],
                    'valorunitario'	       => $itens_servico['valorUnitario'],
                    'subtotal'	           => $itens_servico['subtotal'],
                    'custo'                => $itens_servico['valorCusto'],
                    'subtotalcusto'    	   => $itens_servico['subtotalCusto'],
                    'valorresultadomensal' => $itens_servico['valorResultadoMensal'],
                    'valorcustomensal'     => $itens_servico['valorCustoMensal'],
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
                    'produto_id'           => null,
                    'quantidade'	       => $itens_produto['quantidade'],
                    'valorunitario'	       => $itens_produto['valorUnitario'],
                    'subtotal'	           => $itens_produto['subtotal'],
                    'custo'                => $itens_produto['valorCusto'],
                    'subtotalcusto'    	   => $itens_produto['subtotalCusto'],
                    'valorresultadomensal' => $itens_produto['valorResultadoMensal'],
                    'valorcustomensal'     => $itens_produto['valorCustoMensal']
                ]);
                $historicoOrcamentoProduto = HistoricoOrcamentoServico::firstOrCreate([
                    'orcamentoproduto_id'   => $orcamentoProduto->id,
                    'historicoorcamento_id' => $historico->id
                ]);
            }
        }
    }
}
