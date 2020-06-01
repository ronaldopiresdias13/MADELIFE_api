<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\User;
use App\Banco;
use App\Email;
use App\Pessoa;
use App\Acesso;
use App\Cidade;
use App\Formacao;
use App\Telefone;
use App\Endereco;
use App\Beneficio;
use App\UserAcesso;
use App\PessoaEmail;
use App\Profissional;
use App\Dadosbancario;
use App\PessoaTelefone;
use App\PessoaEndereco;
use App\Horariotrabalho;
use App\Dadoscontratual;
use App\ProfissionalFormacao;
use App\ProfissionalConvenio;
use App\ProfissionalBeneficio;
use App\Http\Controllers\Controller;

class ProfissionaisController extends Controller
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
                    $itens = Profissional::where(
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
            $itens = Profissional::where('id', 'like', '%');
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
        if ($request['pessoa']) {
            $pessoa = Pessoa::where(
                'cpfcnpj', $request['pessoa']['cpfcnpj']
            )->where(
                'empresa_id', $request['pessoa']['empresa_id']
            )->first();
        } else if ($request['pessoa_id']) {
            $pessoa = Pessoa::find($request['pessoa_id']);
        }

        $profissional = null;

        if ($pessoa) {
            $profissional = Profissional::firstWhere(
                'pessoa_id', $pessoa->id,
            );
        }

        if ($profissional) {
            return response()->json('profissional já existe!', 400)->header('Content-Type', 'text/plain');
        }

        $profissional = Profissional::create([
            'pessoafisica' => 1,
            'empresa_id'   => 1,
            'pessoa_id'    => Pessoa::updateOrCreate(
                [
                    'id' => ($request['pessoa']['id'] != '')? $request['pessoa']['id'] : null,
                ],
                [
                    'empresa_id'  => $request['pessoa']['empresa_id' ],
                    'nome'        => $request['pessoa']['nome'       ],
                    'nascimento'  => $request['pessoa']['nascimento' ],
                    'tipo'        =>                    'profissional',
                    'cpfcnpj'     => $request['pessoa']['cpfcnpj'    ],
                    'rgie'        => $request['pessoa']['rgie'       ],
                    'observacoes' => $request['pessoa']['observacoes'],
                    'perfil'      => $request['pessoa']['perfil'     ],
                    'status'      => $request['pessoa']['status'     ],
                ]
            )->id,
            'sexo'                        => $request['sexo'                  ],
            'setor_id'                    => $request['setor_id'              ],
            'cargo_id'                    => $request['cargo_id'              ],
            'pis'                         => $request['pis'                   ],
            'numerocarteiratrabalho'      => $request['numerocarteiratrabalho'],
            'numerocnh'                   => $request['numerocnh'             ],
            'categoriacnh'                => $request['categoriacnh'          ],
            'validadecnh'                 => $request['validadecnh'           ],
            'numerotituloeleitor'         => $request['numerotituloeleitor'   ],
            'zonatituloeleitor'           => $request['zonatituloeleitor'     ],
            'dadoscontratuais_id'         => Dadoscontratual::create([
                'tiposalario'             => $request['dadoscontratuais']['tiposalario'],
                'salario'                 => $request['dadoscontratuais']['salario'    ],
                'cargahoraria'            => $request['dadoscontratuais']['cargahoraria'],
                'insalubridade'           => $request['dadoscontratuais']['insalubridade'],
                'percentualinsalubridade' => $request['dadoscontratuais']['percentualinsalubridade'],
                // 'cargahoraria'            => null,
                // 'insalubridade'           => 0,
                // 'percentualinsalubridade' => null,
                'admissao'                => $request['dadoscontratuais']['admissao'],
                'demissao'                => $request['dadoscontratuais']['demissao'],
            ])->id,
        ]);
        if($request['formacoes']){
            foreach ($request['formacoes'] as $key => $formacao) {
                $profissional_formacao = ProfissionalFormacao::firstOrCreate([
                    'profissional_id' => $profissional->id       ,
                    'formacao_id'     => $formacao['formacao_id'],
                ]);
            }
        }
        if($request['beneficios']){
            foreach ($request['beneficios'] as $key => $beneficio) {
                $profissional_beneficio = ProfissionalBeneficio::firstOrCreate([
                    'profissional_id' => $profissional->id,
                    'beneficio_id'    => $beneficio['beneficio_id']
                ]);
            }
        }
        if($request['convenios']){
            foreach ($request['convenios'] as $key => $convenio) {
                $profissional_convenio = ProfissionalConvenio::firstOrCreate([
                    'profissional_id' => $profissional->id,
                    'convenio_id'    => $convenio['convenio_id']
                ]);
            }
        }
        if($request['dadosBancario']){
            foreach ($request['dadosBancario'] as $key => $dadosbancario) {
                $dados_bancario = Dadosbancario::firstOrCreate([
                    'empresa_id'  => $request["pessoa"]['empresa_id'  ],
                    'banco_id'    => $dadosbancario['banco_id'  ],
                    'agencia'     => $dadosbancario['agencia'],
                    'conta'       => $dadosbancario['conta'  ],
                    'digito'      => $dadosbancario['digito' ],
                    'tipoconta'   => $dadosbancario['tipoconta' ],
                    'pessoa_id'   => $profissional->pessoa_id,
                ]);
            }
        }

        if ($request['pessoa']['enderecos']) {
            foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                $pessoa_endereco = PessoaEndereco::firstOrCreate([
                    'pessoa_id'   => $profissional->pessoa_id,
                    'endereco_id' => Endereco::firstOrCreate(
                        [
                            'cep'         => $endereco['cep'        ],
                            'cidade_id'   => $endereco['cidade_id'  ],
                            'rua'         => $endereco['rua'        ],
                            'bairro'      => $endereco['bairro'     ],
                            'numero'      => $endereco['numero'     ],
                            'complemento' => $endereco['complemento'],
                            'tipo'        => $endereco['tipo'       ],
                            'descricao'   => $endereco['descricao'  ],
                        ]
                    )->id,
                ]);
            }
        }

        if ($request['pessoa']['telefones']) {
            foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                $pessoa_telefone = PessoaTelefone::firstOrCreate([
                    'pessoa_id'   => $profissional->pessoa_id,
                    'telefone_id' => Telefone::firstOrCreate(
                        [
                            'telefone'  => $telefone['telefone' ],
                        ])->id,
                    'tipo'      => $telefone['tipo'     ],
                    'descricao' => $telefone['descricao'],
                ]);
            }
        }

        if ($request['pessoa']['emails']) {
            foreach ($request['pessoa']['emails'] as $key => $email) {
                $pessoa_email = PessoaEmail::firstOrCreate([
                    'pessoa_id' => $profissional->pessoa_id,
                    'email_id'  => Email::firstOrCreate(
                        [
                            'email'  => $email['email'],
                        ])->id,
                    'tipo'      => $email['tipo'     ],
                    'descricao' => $email['descricao'],
                ]);
            }
        }

        if ($request['pessoa']['users']) {
            $user = User::create([
                'empresa_id' =>        $request['empresa_id']                  ,
                'cpfcnpj'    =>        $request['pessoa']['users']['cpfcnpj' ] ,
                'email'      =>        $request['pessoa']['users']['email'   ] ,
                'password'   => bcrypt($request['pessoa']['users']['password']),
                'pessoa_id'  =>        $profissional->pessoa_id ,
            ]);
            foreach ($user['acessos'] as $key => $acesso) {
                $user_acesso = UserAcesso::firstOrCreate([
                    'user_id'   => $user->id,
                    'acesso_id' => Acesso::firstWhere('id', $acesso)->id,
                ]);
            }
        }

        return $profissional;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Profissional $profissionai)
    {
        $iten = $profissionai;

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
     * @param  \App\Profissional  $profissionai
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profissional $profissionai)
    {
        $profissionai->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profissional  $profissionai
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profissional $profissionai)
    {
        $profissionai->delete();
    }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function migracao(Request $request)
    // {

    //         // $table->string('secaotituloeleitor')->nullable();
    //         // $table->unsignedBigInteger('dadoscontratuais_id')->nullable();
    //         // $table->foreign('dadoscontratuais_id')->references('id')->on('dadoscontratuais')->onDelete('cascade');
    //     $profissional = Profissional::firstOrCreate([
    //         'pessoa_id' => Pessoa::firstOrCreate(
    //             [
    //                 'cpfcnpj' => $request['profissional']['dadosPf']['cpf']['numero'],
    //             ],
    //             [
    //                 'nome'        => $request['profissional']['dadosPf']['nome'],
    //                 'nascimento'  => $request['profissional']['dadosPf']['nascimento'],
    //                 'tipo'        => 'Profissional',
    //                 'rgie'        => $request['profissional']['dadosPf']['rg']['numero'],
    //                 'observacoes' => $request['profissional']['observacoes'],
    //                 'status'      => $request['profissional']['status'],
    //             ]
    //         )->id,
    //         'pessoafisica'           => true,
    //         'sexo'                   => $request['profissional']['dadosPf']['sexo'],
    //         'pis'                    => $request['profissional']['dadosProf']['pis'],
    //         'setor_id'               => null,
    //         'cargo_id'               => null,
    //         'numerocarteiratrabalho' => $request['profissional']['dadosProf']['numeroCarteiraTrabalho'],
    //         'numerocnh'              => $request['profissional']['dadosProf']['numeroCnh'],
    //         'categoriacnh'           => null,
    //         'validadecnh'            => $request['profissional']['dadosProf']['validadeCnh'],
    //         'numerotituloeleitor'    => $request['profissional']['dadosProf']['tituloEleitor'],
    //         'zonatituloeleitor'      => $request['profissional']['dadosProf']['zonaTitulo'],
    //         'secaotituloeleitor'     => $request['profissional']['dadosProf']['secaoTitulo'],
    //         'dadoscontratuais_id'    => Dadoscontratual::firstOrCreate([
    //             'tiposalario'             => $request['profissional']['dadosContratuais']['tipoSalario'],
    //             'salario'                 => $request['profissional']['dadosContratuais']['salario'],
    //             'cargahoraria'            => $request['profissional']['dadosContratuais']['horasMensais'],
    //             'insalubridade'           => 0,
    //             'percentualinsalubridade' => null,
    //             'admissao'                => $request['profissional']['dadosContratuais']['dataAdmissao'],
    //             'demissao'                => $request['profissional']['dadosContratuais']['dataDemissao']
    //         ])->id
    //     ]);
    //     if($request['profissional']['dadosProf']['formacao'] != null){
    //         $profissional_formacao = ProfissionalFormacao::firstOrCreate([
    //             'profissional_id' => $profissional->id,
    //             'formacao_id'  => Formacao::firstOrCreate(['descricao' => $request['profissional']['dadosProf']['formacao']['descricao']])->id,
    //         ]);
    //     }
        
        
    //     $usercpf = User::firstWhere(
    //         'cpfcnpj' , $request['profissional']['dadosPf']['cpf']['numero']
    //     );
    //     $useremail = User::firstWhere(
    //         'email', $request['profissional']['contato']['email']
    //     );

    //     if ($usercpf || $useremail) {
    //         // foreach ($request['conta']['grupos'] as $key => $acesso) {
    //         //     $a = UserAcesso::updateOrCreate(
    //         //         ['user_id'  => $usercpf->id, 'acesso_id' => Acesso::firstOrCreate(['nome' => $acesso])->id]
    //         //     );
    //         // }
    //     } else {
    //         foreach ($request['conta']['grupos'] as $key => $value) {
    //             $teste = UserAcesso::firstOrCreate([
    //                 'user_id'  => $user = User::firstOrCreate([
    //                     'cpfcnpj' => $request['profissional']['dadosPf']['cpf']['numero'],
    //                     'email'   => $request['profissional']['contato']['email'],
    //                     'pessoa_id'  => $profissional->pessoa_id,
    //                     'password' => bcrypt($request['conta']['senha']),
    //                 ])->id, 
    //                 'acesso_id' => Acesso::firstOrCreate(['nome' => $value])->id]
    //             );
    //         }
    //     }
        
    //     foreach ($request['profissional']['horarioTrabalho'] as $key => $hora) {
    //         $horario = Horariotrabalho::firstOrCreate([
    //             'diasemana'       => $hora['diaSemana'],
    //             'horarioentrada'  => $hora['horarioEntrada'],
    //             'horariosaida'    => $hora['intervaloSaida'],
    //             'profissional_id' =>$profissional->id
    //         ]);
    //     }
    //     foreach ($request['profissional']['dadosContratuais']['beneficios'] as $key => $beneficio) {
    //         $profissional_beneficios = ProfissionalBeneficio::firstOrCreate([
    //             'profissional_id' =>$profissional->id,
    //             'beneficio_id'    => Beneficio::firstOrCreate([
    //                 'descricao'  => $beneficio['beneficios'],
    //                 // 'valor'      => $beneficio['valor'],
    //                 'empresa_id' => 1,
    //             ])->id
    //         ]);
    //     }
    //     if($request['profissional']['dadosBancario']['banco'] != null && $request['profissional']['dadosBancario']['banco']['codigo'] != null){
    //         $dados_bancario = Dadosbancario::firstOrCreate([
    //             'banco_id' => Banco::firstOrCreate(
    //                 [
    //                     'codigo' => ($request['profissional']['dadosBancario']['banco']['codigo'] == null || $request['profissional']['dadosBancario']['banco']['codigo'] == "") ? '000' : $request['profissional']['dadosBancario']['banco']['codigo'],
    //                 ],
    //                 [
    //                     'descricao' => ($request['profissional']['dadosBancario']['banco']['codigo'] == null || $request['profissional']['dadosBancario']['banco']['codigo'] == "") ? 'Outros' : $request['profissional']['dadosBancario']['banco']['descricao']
    //                 ]
    //             )->id,
    //             'pessoa_id'    => $profissional->pessoa_id,
    //             'agencia'   => $request['profissional']['dadosBancario']['agencia'  ],
    //             'conta'     => $request['profissional']['dadosBancario']['conta'    ],
    //             'digito'    => $request['profissional']['dadosBancario']['digito'   ],
    //             'tipoconta' => $request['profissional']['dadosBancario']['tipoConta'],
    //         ]);
    //     }

    //     if ($request['profissional']['contato']['telefone'] != null && $request['profissional']['contato']['telefone'] != "") {
    //         $pessoa_telefones = PessoaTelefone::firstOrCreate([
    //             'pessoa_id'   => $profissional->pessoa_id,
    //             'telefone_id' => Telefone::firstOrCreate(
    //                 [
    //                     'telefone' => $request['profissional']['contato']['telefone'],
    //                 ]
    //             )->id,
    //         ]);
    //     }
    //     if ($request['profissional']['contato']['celular'] != null && $request['profissional']['contato']['celular'] != "") {
    //         $pessoa_telefones = PessoaTelefone::firstOrCreate([
    //             'pessoa_id'   => $profissional->pessoa_id,
    //             'telefone_id' => Telefone::firstOrCreate(
    //                 [
    //                     'telefone' => $request['profissional']['contato']['celular'],
    //                 ]
    //             )->id,
    //         ]);
    //     }
    //     if($request['profissional']['contato']['email']){
    //         $pessoa_emails = PessoaEmail::firstOrCreate([
    //             'pessoa_id' => $profissional->pessoa_id,
    //             'email_id'  => Email::firstOrCreate(
    //                 [
    //                     'email' => $request['profissional']['contato']['email'],
    //                 ],
    //                 [
    //                     'tipo' => 'pessoal',
    //                 ]
    //             )->id,
    //         ]);
    //     }
        
        
    //     $cidade = Cidade::where('nome', $request['profissional']['endereco']['cidade'])->where('uf', $request['profissional']['endereco']['uf'])->first();

    //     $pessoa_endereco = PessoaEndereco::firstOrCreate([
    //         'pessoa_id'   => $profissional->pessoa_id,
    //         'endereco_id' => Endereco::firstOrCreate(
    //             [
    //                 'cep'         => $request['profissional']['endereco']['cep'],
    //                 'cidade_id'   => ($cidade) ? $cidade->id : null,
    //                 'rua'         => $request['profissional']['endereco']['rua'],
    //                 'bairro'      => $request['profissional']['endereco']['bairro'],
    //                 'numero'      => $request['profissional']['endereco']['numero'],
    //                 'complemento' => $request['profissional']['endereco']['complemento'],
    //                 'tipo'        => 'Residencial',
    //             ]
    //         )->id,
    //     ]);
    // }
}
