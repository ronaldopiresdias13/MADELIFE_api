<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PessoaTelefone;
use App\Models\Telefone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PessoaTelefoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
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
            $itens = PessoaTelefone::with($with)->where('ativo', true);
        } else {
            $itens = PessoaTelefone::where('ativo', true);
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
        DB::transaction(function () use ($request) {
            PessoaTelefone::updateOrCreate(
                [
                    'pessoa_id' => $request->pessoa_id,
                    'telefone_id'  => Telefone::firstOrCreate(
                        ['telefone' => $request->telefone]
                    )->id,
                ],
                [
                    'tipo'      => $request['pivot']['tipo'],
                    'descricao' => $request['pivot']['descricao'],
                    'ativo'     => true
                ]
            );
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PessoaTelefone  $pessoaTelefone
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, PessoaTelefone $pessoaTelefone)
    {
        $iten = $pessoaTelefone;

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
     * @param  \App\PessoaTelefone  $pessoaTelefone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PessoaTelefone $pessoaTelefone)
    {
        DB::transaction(function () use ($request, $pessoaTelefone) {
            $pessoaTelefone->telefone_id  = Telefone::firstOrCreate(['telefone' => $request['telefone']])->id;
            $pessoaTelefone->tipo      = $request['pivot']['tipo'];
            $pessoaTelefone->descricao = $request['pivot']['descricao'];
            $pessoaTelefone->ativo     = true;
            $pessoaTelefone->save();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PessoaTelefone  $pessoaTelefone
     * @return \Illuminate\Http\Response
     */
    public function destroy(PessoaTelefone $pessoaTelefone)
    {
        $pessoaTelefone->ativo = false;
        $pessoaTelefone->save();
    }
}
