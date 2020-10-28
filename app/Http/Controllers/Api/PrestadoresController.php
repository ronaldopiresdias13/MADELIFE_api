<?php

namespace App\Http\Controllers\Api;

use App\Conselho;
use App\Dadosbancario;
use App\Email;
use App\Endereco;
use App\Http\Controllers\Controller;
use App\Escala;
use App\Pessoa;
use App\PessoaEmail;
use App\PessoaEndereco;
use App\PessoaTelefone;
use App\Prestador;
use App\PrestadorFormacao;
use App\Telefone;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// use Illuminate\Support\Facades\DB;

class PrestadoresController extends Controller
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
            $itens = Prestador::with($with)->where('ativo', true);
        } else {
            $itens = Prestador::where('ativo', true);
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
        $cpfcnpj = Pessoa::where(
            'cpfcnpj',
            $request['pessoa']['cpfcnpj']
        );
        $email = Email::where('email', $request['user']['email']);
        if ($cpfcnpj) {
            return response()->json('Usuário já existe!', 400)->header('Content-Type', 'text/plain');
        }
        if ($email) {
            return response()->json('Usuário já existe!', 400)->header('Content-Type', 'text/plain');
        } else {
            $user = User::create(
                [
                    'cpfcnpj'    => $request['cpfcnpj'],
                    'email'      => $request['user']['email'],
                    'password'   =>  bcrypt($request['user']['password']),
                    'pessoa_id'  => Pessoa::create(
                        [
                            'nome'       => $request['nome'],
                            'nascimento' => $request['nascimento'],
                            'tipo'       => $request['tipo'],
                            'cpfcnpj'    => $request['cpfcnpj'],
                            'status'     => $request['status']
                        ]
                    )->id
                ]
            );
            PessoaEmail::firstOrCreate([
                'pessoa_id' => $user->pessoa_id,
                'email_id'  => Email::firstOrCreate(
                    [
                        'email' => $user->email,
                    ]
                )->id,
                'tipo'      => 'Pessoal',
            ]);
            Conselho::create(
                [
                    'instituicao' => $request['conselho']['instituicao'],
                    'numero'      => $request['conselho']['numero'],
                    'pessoa_id'   => $user->pessoa_id
                ]
            );
            PrestadorFormacao::create(
                [
                    'prestador_id' => Prestador::create(
                        [
                            'pessoa_id' => $user->pessoa_id,
                            'sexo'      => $request['prestador']['sexo']
                        ]
                    )->id,
                    'formacao_id'  => $request['prestador']['formacao_id']
                ]
            );
        }

        // $prestador = new Prestador();
        // $prestador->pessoa             = $request->pessoa;
        // $prestador->fantasia           = $request->fantasia;
        // $prestador->sexo               = $request->sexo;
        // $prestador->pis                = $request->pis;
        // $prestador->formacao           = $request->formacao;
        // $prestador->cargo              = $request->cargo;
        // $prestador->curriculo          = $request->curriculo;
        // $prestador->certificado        = $request->certificado;
        // $prestador->meiativa           = $request->meiativa ? $request->meiativa : 0;
        // $prestador->dataverificacaomei = $request->dataverificacaomei ? $request->dataverificacaomei : null;
        // $prestador->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Prestador $prestador)
    {
        $iten = $prestador;

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
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prestador $prestador)
    {
        DB::transaction(function () use ($request, $prestador) {
            $prestador = Prestador::updateOrCreate(
                [
                    'id' => $request['id'],
                ],
                [
                    'fantasia'           => $request['fantasia'],
                    'sexo'               => $request['sexo'],
                    'pis'                => $request['pis'],
                    'cargo_id'           => $request['cargo_id'],
                    'certificado'        => $request['certificado'],
                    'meiativa'           => $request['meiativa'],
                    'dataverificacaomei' => $request['dataverificacaomei'],

                    'pessoa_id'    => Pessoa::updateOrCreate(
                        [
                            // 'id' => ($request['pessoa']['id'] != '') ? $request['pessoa']['id'] : null,
                            'id' => $request['pessoa_id'],
                        ],
                        [
                            // 'empresa_id'  => $request['pessoa']['empresa_id'],
                            'nome'        => $request['pessoa']['nome'],
                            'nascimento'  => $request['pessoa']['nascimento'],
                            'tipo'        => $request['pessoa']['tipo'],
                            'rgie'        => $request['pessoa']['rgie'],
                            'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
                            'observacoes' => $request['pessoa']['observacoes'],
                            'perfil'      => $request['pessoa']['perfil'],
                            'status'      => $request['pessoa']['status'],
                        ]
                    )->id,
                ]
            );
            if ($request['pessoa']['dadosbancario']) {
                foreach ($request['pessoa']['dadosbancario'] as $key => $dadosbancario) {
                    Dadosbancario::firstOrCreate([
                        'empresa_id'  => $request["pessoa"]['empresa_id'],
                        'banco_id'    => $dadosbancario['banco_id'],
                        'agencia'     => $dadosbancario['agencia'],
                        'conta'       => $dadosbancario['conta'],
                        'digito'      => $dadosbancario['digito'],
                        'tipoconta'   => $dadosbancario['tipoconta'],
                        'pessoa_id'   => $prestador->pessoa_id,
                    ]);
                }
            }
            if ($request['formacoes']) {
                foreach ($request['formacoes'] as $key => $formacao) {
                    PrestadorFormacao::firstOrCreate([
                        'prestador_id' => $prestador->id,
                        'formacao_id'     => $formacao['formacao_id'],
                    ]);
                }
            }
            if ($request['pessoa']['conselhos']) {
                foreach ($request['pessoa']['conselhos'] as $key => $conselho) {
                    Conselho::firstOrCreate([
                        'id'          => $conselho['id'],
                        'instituicao' => $conselho['instituicao'],
                        'uf'          => $conselho['uf'],
                        'numero'      => $conselho['numero'],
                        'pessoa_id'   => $prestador->pessoa_id,
                    ]);
                }
            }
            if ($request['pessoa']['enderecos']) {
                foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                    PessoaEndereco::firstOrCreate([
                        'pessoa_id'   => $prestador->pessoa_id,
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
                    PessoaTelefone::firstOrCreate([
                        'pessoa_id'   => $prestador->pessoa_id,
                        'telefone_id' => Telefone::firstOrCreate(
                            [
                                'telefone'  => $telefone['telefone'],
                            ]
                        )->id,
                        'tipo'      => $telefone['pivot']['tipo'],
                        'descricao' => $telefone['pivot']['descricao'],
                    ]);
                }
            }

            if ($request['pessoa']['emails']) {
                foreach ($request['pessoa']['emails'] as $key => $email) {
                    PessoaEmail::firstOrCreate([
                        'pessoa_id' => $prestador->pessoa_id,
                        'email_id'  => Email::firstOrCreate(
                            [
                                'email' => $email['email'],
                            ]
                        )->id,
                        'tipo'      => $email['pivot']['tipo'],
                        'descricao' => $email['pivot']['descricao'],
                    ]);
                }
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestador $prestador)
    {
        $prestador->ativo = false;
        $prestador->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function meuspacientes(Prestador $prestador)
    {
        $escalas = Escala::where('prestador_id', $prestador->id)
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'homecares.paciente_id', '=', 'pacientes.id')
            ->join('pessoas', 'pacientes.pessoa_id', '=', 'pessoas.id')
            ->select('pessoas.nome')
            ->where('homecares.ativo', true)
            ->groupBy('pessoas.nome')
            ->orderBy('pessoas.nome')
            ->get();
        return $escalas;
    }
}
