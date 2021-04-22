<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Email;
use App\Models\Acesso;
use App\Models\Pessoa;
use App\Models\Conselho;
use App\Models\Prestador;
use App\Models\UserAcesso;
use App\Models\PessoaEmail;
use App\Models\PrestadorFormacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
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
            $itens = User::with($with)->where('ativo', true);
        } else {
            $itens = User::where('ativo', true);
        }

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
        $userCpf   = User::firstWhere('cpfcnpj', $request['cpfcnpj']);
        $userEmail = User::firstWhere('email', $request['email']);
        $user = null;
        if ($userCpf) {
            $user = $userCpf;
        } elseif ($userEmail) {
            $user = $userEmail;
        }

        if ($user) {
            DB::transaction(function () use ($request, $user) {
                $user->update(
                    [
                        'cpfcnpj'    => $request['cpfcnpj'],
                        'email'      => $request['email'],
                        'password'   =>  bcrypt($request['password']),
                        'pessoa_id'  => $request->pessoa_id
                    ]
                );

                if ($request['acessos']) {
                    foreach ($request['acessos'] as $key => $acesso) {
                        $user_acesso = UserAcesso::firstOrCreate([
                            'user_id'   => $user->id,
                            'acesso_id' => $acesso,
                        ]);
                    }
                }
            });
        } else {
            DB::transaction(function () use ($request) {
                $user = User::create(
                    [
                        'cpfcnpj'    => $request['cpfcnpj'],
                        'email'      => $request['email'],
                        'password'   =>  bcrypt($request['password']),
                        'pessoa_id'  => $request['pessoa_id']
                    ]
                );

                if ($request['acessos']) {
                    foreach ($request['acessos'] as $key => $acesso) {
                        $user_acesso = UserAcesso::firstOrCreate([
                            'user_id'   => $user->id,
                            'acesso_id' => $acesso,
                        ]);
                    }
                }
            });
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
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Request $request, user $user)
    {
        $user->email = $request['email'];
        $user->update();
    }
}
