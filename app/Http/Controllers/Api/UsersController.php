<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Email;
use App\Acesso;
use App\Pessoa;
use App\Conselho;
use App\Prestador;
use App\UserAcesso;
use App\PessoaEmail;
use App\PrestadorFormacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = User::where('ativo', true);

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                // if ($key == 0) {
                //     $itens = User::where(
                //         ($where['coluna']) ? $where['coluna'] : 'id',
                //         ($where['expressao']) ? $where['expressao'] : 'like',
                //         ($where['valor']) ? $where['valor'] : '%'
                //     );
                // } else {
                $itens->where(
                    ($where['coluna']) ? $where['coluna'] : 'id',
                    ($where['expressao']) ? $where['expressao'] : 'like',
                    ($where['valor']) ? $where['valor'] : '%'
                );
                // }
            }
            // } else {
            //     $itens = User::where('id', 'like', '%');
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
                    'empresa_id' => 1,
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
            $pessoa_email = PessoaEmail::firstOrCreate([
                'pessoa_id' => $user->pessoa_id,
                'email_id'  => Email::firstOrCreate(
                    [
                        'email' => $user->email,
                    ]
                )->id,
                'tipo'      => 'Pessoal',
            ]);
            $conselho = Conselho::create(
                [
                    'instituicao' => $request['conselho']['instituicao'],
                    'numero'      => $request['conselho']['numero'],
                    'pessoa_id'   => $user->pessoa_id
                ]
            );
            $formacao = PrestadorFormacao::create(
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, user $user)
    {
        $iten = $user;

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
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {
        foreach ($request->acessos as $key => $value) {
            $teste = UserAcesso::updateOrCreate(
                ['user'  => $user->id, 'acesso' => Acesso::FirstOrCreate(['nome' => $value['nome']])->id]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user)
    {
        $user->ativo = false;
        $user->save();
    }
}
