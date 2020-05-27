<?php

namespace App\Http\Controllers\Api;

use App\Email;
use App\Pessoa;
use App\Remocao;
use App\Servico;
use App\Telefone;
use App\Homecare;
use App\Orcamento;
use App\RemocaoEmail;
use App\HomecareEmail;
use App\Orcamentocusto;
use App\RemocaoTelefone;
use App\OrcamentoServico;
use App\OrcamentoProduto;
use App\HomecareTelefone;
use App\Historicoorcamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        // $orcamentos = Orcamento::all();
        // foreach ($orcamentos as $key => $orcamento) {
        //     // $orcamento->historicos;
        //     foreach ($orcamento->historicos as $key => $historico) {
        //         $historico->orcamentoservicos;
        //         $historico->orcamentoprodutos;
        //     }
        // }
        // return $orcamentos;
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
                foreach ($request['adicionais'] as $key => $adicional) { // Percorrer os adicionais
                    if (is_string($adicional)) { // Se String, chama o adicional
                        $iten[$adicional];
                    } else { // Se Array Percorrer o array
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) { // Se primeiro item
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
        // $teste = (String)$request->all();
        // return gettype(json_encode($request->all()));

        $orcamento = Orcamento::updateOrCreate(
        [
            'id' => ($request['id'] != '')? $request['id'] : null,
        ],
        [
            'empresa_id'        => $request['empresa_id'       ],
            'cliente_id'        => $request['cliente_id'       ],
            'numero'            => $request['numero'           ],
            'processo'          => $request['processo'         ],
            'cidade_id'         => $request['cidade_id'        ],
            'tipo'              => $request['tipo'             ],
            'data'              => $request['data'             ],
            'unidade'           => $request['unidade'          ],
            'quantidade'        => $request['quantidade'       ],
            'situacao'          => $request['situacao'         ],
            'descricao'         => $request['descricao'        ],
            'valortotalservico' => $request['valortotalservico'],
            'valortotalcusto'   => $request['valortotalcusto'  ],
            'valortotalproduto' => $request['valortotalproduto'],
            'observacao'        => $request['observacao'       ],
            'status'            => 1
        ]);

        if ($request['servicos']) {
            foreach ($request['servicos'] as $key => $servico) {
                $orcamento_servico = OrcamentoServico::updateOrCreate(
                [
                    'orcamento_id'         => $orcamento->id                  ,
                    'servico_id'           => $servico['servico_id'          ],
                ],
                [
                    'quantidade'	       => $servico['quantidade'          ],
                    'frequencia'	       => $servico['frequencia'          ],
                    'basecobranca'	       => $servico['basecobranca'        ],
                    'valorunitario'	       => $servico['valorunitario'       ],
                    'custo'                => $servico['custo'               ],
                    'subtotal'	           => $servico['subtotal'            ],
                    'subtotalcusto'    	   => $servico['subtotalcusto'       ],
                    'icms'	               => $servico['icms'                ],
                    'inss'                 => $servico['inss'                ],
                    'iss'                  => $servico['iss'                 ],
                    'valorcustomensal'     => $servico['valorcustomensal'    ],
                    'valorresultadomensal' => $servico['valorresultadomensal'],
                ]);
            }
        }

        if ($request['produtos']) {
            foreach ($request['produtos'] as $key => $produto) {
                $orcamento_produto = OrcamentoProduto::updateOrCreate([
                    'orcamento_id'         => $orcamento->id                  ,
                    'produto_id'           => $produto['produto_id'          ],
                    'quantidade'	       => $produto['quantidade'          ],
                    'valorunitario'	       => $produto['valorunitario'       ],
                    'custo'                => $produto['custo'               ],
                    'subtotal'	           => $produto['subtotal'            ],
                    'subtotalcusto'    	   => $produto['subtotalcusto'       ],
                    'valorresultadomensal' => $produto['valorresultadomensal'],
                    'valorcustomensal'     => $produto['valorcustomensal'    ]
                ]);
            }
        }

        if ($request['custos']) {
            foreach ($request['custos'] as $key => $custo) {
                $orcamentocusto = Orcamentocusto::create([
                    'orcamento_id'  => $orcamento->id,
                    'descricao'     => $custo['descricao'    ],
                    'quantidade'    => $custo['quantidade'   ],
                    'unidade'       => $custo['unidade'      ],
                    'valorunitario'	=> $custo['valorunitario'],
                    'valortotal'    => $custo['valortotal'   ],
                ]);
            }
        }

        if ($request['homecare']) {
            $homecare = Homecare::create([
                'orcamento_id' => $orcamento->id,
                'nome'         => $request['homecare']['nome'      ],
                'sexo'         => $request['homecare']['sexo'      ],
                'nascimento'   => $request['homecare']['nascimento'],
                'cpfcnpj'	   => $request['homecare']['cpf'       ],
                'rgie'         => $request['homecare']['rg'        ],
                'endereco'     => $request['homecare']['endereco'  ],
                'cidade_id'    => $request['homecare']['cidade'    ],
                'observacao'   => $request['homecare']['observacao'],
            ]);

            if ($request['homecare']['telefone']) {
                $homecare_telefone = HomecareTelefone::create([
                    'homecare_id' => $homecare->id,
                    'telefone_id' => Telefone::create(['telefone' => $request['homecare']['telefone']])->id,
                ]);
            }
            if ($request['homecare']['celular']) {
                $homecare_telefone = homecareTelefone::create([
                    'homecare_id' => $homecare->id,
                    'telefone_id' => Telefone::create(['telefone' => $request['homecare']['celular']])->id,
                ]);
            }
            if ($request['homecare']['email']) {
                $homecare_email = HomecareEmail::create([
                    'homecare_id' => $homecare->id,
                    'email_id'    => Email::create(['email' => $request['homecare']['email']])->id,
                ]);
            }
        }

        if ($request['remocao']) {
            $remocao = Remocao::create([
                'orcamento_id'    => $orcamento->id,
                'nome'            => $request['remocao']['nome'           ],
                'sexo'            => $request['remocao']['sexo'           ],
                'nascimento'      => $request['remocao']['nascimento'     ],
                'cpfcnpj'	      => $request['remocao']['cpf'            ],
                'rgie'            => $request['remocao']['rg'             ],
                'enderecoorigem'  => $request['remocao']['enderecoorigem' ],
                'cidadeorigem'    => $request['remocao']['cidadeorigem'   ],
                'enderecodestino' => $request['remocao']['enderecodestino'],
                'cidadedestino'   => $request['remocao']['cidadedestino'  ],
                'observacao'      => $request['remocao']['observacao'     ],
            ]);

            if ($request['remocao']['telefone']) {
                $remocao_telefone = RemocaoTelefone::create([
                    'remocao_id'  => $remocao->id,
                    'telefone_id' => Telefone::create(['telefone' => $request['remocao']['telefone']])->id,
                ]);
            }
            if ($request['remocao']['celular']) {
                $remocao_telefone = RemocaoTelefone::create([
                    'remocao_id'  => $remocao->id,
                    'telefone_id' => Telefone::create(['telefone' => $request['remocao']['celular']])->id,
                ]);
            }
            if ($request['remocao']['email']) {
                $remocao_email = RemocaoEmail::create([
                    'remocao_id' => $remocao->id,
                    'email_id'   => Email::create(['email' => $request['remocao']['email']])->id,
                ]);
            }
        }

        if ($request['evento']) {
            $evento = Evento::create([
                'orcamento_id' => $orcamento->id,
                'nome'         => $request['evento']['nome'    ],
                'endereco'     => $request['evento']['endereco'],
                'cep'          => $request['evento']['cep'     ],
                'cidade'       => $request['evento']['cidade'  ],
            ]);

            if ($request['evento']['telefone']) {
                $evento_telefone = EventoTelefone::create([
                    'evento_id'   => $evento->id,
                    'telefone_id' => Telefone::create(['telefone' => $request['evento']['telefone']])->id,
                ]);
            }
            if ($request['evento']['celular']) {
                $evento_telefone = EventoTelefone::create([
                    'evento_id'   => $evento->id,
                    'telefone_id' => Telefone::create(['telefone' => $request['evento']['celular']])->id,
                ]);
            }
            if ($request['evento']['email']) {
                $evento_email = EventoEmail::create([
                    'evento_id' => $evento->id,
                    'email_id'  => Email::create(['email' => $request['evento']['email']])->id,
                ]);
            }
        }

        if ($request['aph']) {
            $aph = Aph::create([
                'orcamento_id' => $orcamento->id,
                'nome'         => $request['aph']['nome'    ],
                'endereco'     => $request['aph']['endereco'],
                'cep'          => $request['aph']['cep'     ],
                'cidade'       => $request['aph']['cidade'  ],
            ]);

            if ($request['aph']['telefone']) {
                $aph_telefone = AphTelefone::create([
                    'aph_id'      => $aph->id,
                    'telefone_id' => Telefone::create(['telefone' => $request['aph']['telefone']])->id,
                ]);
            }
            if ($request['aph']['celular']) {
                $aph_telefone = AphTelefone::create([
                    'aph_id'      => $aph->id,
                    'telefone_id' => Telefone::create(['telefone' => $request['aph']['celular']])->id,
                ]);
            }
            if ($request['aph']['email']) {
                $aph_email = AphEmail::create([
                    'aph_id'   => $aph->id,
                    'email_id' => Email::create(['email' => $request['aph']['email']])->id,
                ]);
            }
        }

        $historicoorcamento = Historicoorcamento::create([
            'orcamento_id' => $orcamento->id,
            'historico'    => json_encode($request->all()),
        ]);
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
