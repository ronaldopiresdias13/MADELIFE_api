<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\PrestadorFormacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PrestadorFormacaoController extends Controller
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
            $itens = PrestadorFormacao::with($with)->where('ativo', true);
        } else {
            $itens = PrestadorFormacao::where('ativo', true);
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
        // return $request;
        $file = $request->file("file");
        $request = json_decode($request->data, true);
        // return $request;
        if ($file && $file->isValid()) {
            $md5 = md5_file($file);
            $caminho = 'certificados/' . $request["prestador_id"];
            $nome = $md5 . '.' . $file->extension();
            $upload = $file->storeAs($caminho, $nome);
            $nomeOriginal = $file->getClientOriginalName();
            if ($upload) {
                DB::transaction(function () use ($request, $caminho, $nome, $nomeOriginal) {
                    $prestador_formacao = PrestadorFormacao::updateOrCreate(
                        [
                            'prestador_id' => $request['prestador_id'],
                            'formacao_id'  => $request['formacao_id'],
                            'caminho'      => $caminho . '/' . $nome,
                            'nome'         => $nomeOriginal,
                        ],
                        [
                            'ativo' => true
                        ]
                    );
                });
                return response()->json('Upload de arquivo bem sucedido!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Erro, Upload não realizado!', 400)->header('Content-Type', 'text/plain');
            }
        } else {
            return response()->json('Arquivo inválido ou corrompido!', 400)->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PrestadorFormacao  $prestadorFormacao
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, PrestadorFormacao $prestadorFormacao)
    {
        $iten = $prestadorFormacao;

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
     * @param  \App\PrestadorFormacao  $prestadorFormacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrestadorFormacao $prestadorFormacao)
    {
        return 'UpDate';
        DB::transaction(function () use ($request, $prestadorFormacao) {
            $prestadorFormacao->prestador_id = $request->prestador_id;
            $prestadorFormacao->formacao_id  = $request->formacao_id;
            $prestadorFormacao->nome         = $request->nome;
            $prestadorFormacao->caminho      = $request->caminho;
            $prestadorFormacao->ativo        = true;
            $prestadorFormacao->save();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PrestadorFormacao  $prestadorFormacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrestadorFormacao $prestadorFormacao)
    {
        DB::transaction(function () use ($prestadorFormacao) {
            $prestadorFormacao->ativo = false;
            $prestadorFormacao->save();
        });
    }

    /**
     * Download the file specified resource.
     *
     * @param  \App\PrestadorFormacao  $prestadorFormacao
     * @return \Illuminate\Http\Response
     */
    public function downloadFile(PrestadorFormacao $prestadorFormacao)
    {
        $file = Storage::get($prestadorFormacao['caminho']);

        $response =  array(
            'nome' => $prestadorFormacao['nome'],
            'file' => base64_encode($file)
        );

        return response()->json($response);
    }
}
