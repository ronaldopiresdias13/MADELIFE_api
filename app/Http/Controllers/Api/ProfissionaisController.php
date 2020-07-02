<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Email;
use App\Pessoa;
use App\Acesso;
use App\Telefone;
use App\Endereco;
use App\UserAcesso;
use App\PessoaEmail;
use App\Profissional;
use App\Dadosbancario;
use App\PessoaTelefone;
use App\PessoaEndereco;
use App\Dadoscontratual;
use Illuminate\Http\Request;
use App\ProfissionalFormacao;
use App\ProfissionalConvenio;
use App\ProfissionalBeneficio;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProfissionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Profissional();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Profissional::where(
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
            $itens = Profissional::where('id', 'like', '%');
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
        if ($request['pessoa']) {
            $pessoa = Pessoa::where(
                'cpfcnpj',
                $request['pessoa']['cpfcnpj']
            )->where(
                'empresa_id',
                $request['pessoa']['empresa_id']
            )->first();
        } elseif ($request['pessoa_id']) {
            $pessoa = Pessoa::find($request['pessoa_id']);
        }

        $profissional = null;

        if ($pessoa) {
            $profissional = Profissional::firstWhere(
                'pessoa_id',
                $pessoa->id,
            );
        }

        if ($profissional) {
            return response()->json('Profissional já existe!', 400)->header('Content-Type', 'text/plain');
        }

        DB::transaction(function () use ($request, $profissional) {
            $profissional = Profissional::create([
                'pessoafisica' => 1,
                'empresa_id'   => 1,
                'pessoa_id'    => Pessoa::firstOrCreate(
                    [
                        // 'id' => ($request['pessoa']['id'] != '') ? $request['pessoa']['id'] : null,
                        'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
                    ],
                    [
                        'empresa_id'  => $request['pessoa']['empresa_id'],
                        'nome'        => $request['pessoa']['nome'],
                        'nascimento'  => $request['pessoa']['nascimento'],
                        'tipo'        =>                    'profissional',
                        'rgie'        => $request['pessoa']['rgie'],
                        'observacoes' => $request['pessoa']['observacoes'],
                        'perfil'      => $request['pessoa']['perfil'],
                        'status'      => $request['pessoa']['status'],
                    ]
                )->id,
                'sexo'                        => $request['sexo'],
                'setor_id'                    => $request['setor_id'],
                'cargo_id'                    => $request['cargo_id'],
                'pis'                         => $request['pis'],
                'numerocarteiratrabalho'      => $request['numerocarteiratrabalho'],
                'numerocnh'                   => $request['numerocnh'],
                'categoriacnh'                => $request['categoriacnh'],
                'validadecnh'                 => $request['validadecnh'],
                'numerotituloeleitor'         => $request['numerotituloeleitor'],
                'zonatituloeleitor'           => $request['zonatituloeleitor'],
                'dadoscontratuais_id'         => Dadoscontratual::create([
                    'tiposalario'             => $request['dadoscontratuais']['tiposalario'],
                    'salario'                 => $request['dadoscontratuais']['salario'],
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
            if ($request['formacoes']) {
                foreach ($request['formacoes'] as $key => $formacao) {
                    $profissional_formacao = ProfissionalFormacao::firstOrCreate([
                        'profissional_id' => $profissional->id,
                        'formacao_id'     => $formacao['formacao_id'],
                    ]);
                }
            }
            if ($request['beneficios']) {
                foreach ($request['beneficios'] as $key => $beneficio) {
                    $profissional_beneficio = ProfissionalBeneficio::firstOrCreate([
                        'profissional_id' => $profissional->id,
                        'beneficio_id'    => $beneficio['beneficio_id']
                    ]);
                }
            }
            if ($request['convenios']) {
                foreach ($request['convenios'] as $key => $convenio) {
                    $profissional_convenio = ProfissionalConvenio::firstOrCreate([
                        'profissional_id' => $profissional->id,
                        'convenio_id'    => $convenio['convenio_id']
                    ]);
                }
            }
            if ($request['dadosBancario']) {
                foreach ($request['dadosBancario'] as $key => $dadosbancario) {
                    $dados_bancario = Dadosbancario::firstOrCreate([
                        'empresa_id'  => $request["pessoa"]['empresa_id'],
                        'banco_id'    => $dadosbancario['banco_id'],
                        'agencia'     => $dadosbancario['agencia'],
                        'conta'       => $dadosbancario['conta'],
                        'digito'      => $dadosbancario['digito'],
                        'tipoconta'   => $dadosbancario['tipoconta'],
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
                                'cep'         => $endereco['cep'],
                                'cidade_id'   => $endereco['cidade_id'],
                                'rua'         => $endereco['rua'],
                                'bairro'      => $endereco['bairro'],
                                'numero'      => $endereco['numero'],
                                'complemento' => $endereco['complemento'],
                                'tipo'        => $endereco['tipo'],
                                'descricao'   => $endereco['descricao'],
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
                                'telefone'  => $telefone['telefone'],
                            ]
                        )->id,
                        'tipo'      => $telefone['tipo'],
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
                                'email' => $email['email'],
                            ]
                        )->id,
                        'tipo'      => $email['tipo'],
                        'descricao' => $email['descricao'],
                    ]);
                }
            }

            if ($request['pessoa']['user']) {
                $user = User::firstOrCreate(
                    [
                        'email'      =>        $request['pessoa']['user']['email'],
                    ],
                    [
                        'empresa_id' =>        $request['empresa_id'],
                        'cpfcnpj'    =>        $request['pessoa']['user']['cpfcnpj'],
                        'password'   => bcrypt($request['pessoa']['user']['password']),
                        'pessoa_id'  =>        $profissional->pessoa_id,
                    ]
                );
                if ($request['pessoa']['user']['acessos']) {
                    foreach ($request['pessoa']['user']['acessos'] as $key => $acesso) {
                        $user_acesso = UserAcesso::firstOrCreate([
                            'user_id'   => $user->id,
                            'acesso_id' => $acesso,
                        ]);
                    }
                }
            }
        });
        // return $profissional;
        return response()->json('Profissional cadastrado com sucesso!', 200)->header('Content-Type', 'text/plain');
    }

    // ["prestador", "pessoa", "conselhos"],
    // "pontos", "servico"

    /**
     * Display the specified resource.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Profissional $profissional)
    {
        $iten = $profissional;

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
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profissional $profissional)
    {
        $profissional->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profissional $profissional)
    {
        $profissional->delete();
    }
}
