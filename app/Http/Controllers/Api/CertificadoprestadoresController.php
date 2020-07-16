<?php

namespace App\Http\Controllers\Api;

use App\Certificadoprestador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificadoprestadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = Certificadoprestador::where('ativo', true);

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
        $file = $request->file('file');
        $request = json_decode($request->data, true);
        if ($file && $file->isValid()) {
            $md5 = md5_file($file);
            $caminho = 'certificadoPrestadores/' . $request['prestador_id'];
            $nome = $md5 . '.' . $file->extension();
            $upload = $file->storeAs($caminho, $nome);
            $nomeOriginal = $file->getClientOriginalName();
            if ($upload) {
                DB::transaction(function () use ($request, $caminho, $nome, $nomeOriginal) {
                    $certificadoprestador = Certificadoprestador::create([
                        'prestador_id' => $request['prestador_id'],
                        'caminho'      => $caminho . '/' . $nome,
                        'nome'         => $nomeOriginal,
                        'data'         => $request['data'],
                        'carga'        => $request['carga'],
                    ]);
                });
                return response()->json('Upload de arquivo bem sucedido!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Erro, Upload não realizado!', 400)->header('Content-Type', 'text/plain');
            }
        } else {
            if ($file == null) {
                DB::transaction(function () use ($request) {
                    $certificadoprestador = Certificadoprestador::create([
                        'prestador_id' => $request['prestador_id'],
                        'caminho'      => '',
                        'nome'         => '',
                        'data'         => $request['data'],
                        'carga'        => $request['carga'],
                    ]);
                });
                return response()->json('Dados salvos com sucesso!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Arquivo inválido ou corrompido!', 400)->header('Content-Type', 'text/plain');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Certificadoprestador  $certificadoprestador
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Certificadoprestador $certificadoprestador)
    {
        $iten = $certificadoprestador;

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
     * @param  \App\Certificadoprestador  $certificadoprestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Certificadoprestador $certificadoprestador)
    {
        $file = $request->file('file');
        $request = json_decode($request->data, true);
        if ($file && $file->isValid()) {
            $md5 = md5_file($file);
            $caminho = 'contratosPrestadores/' . $request['prestador_id'];
            $nome = $md5 . '.' . $file->extension();
            $upload = $file->storeAs($caminho, $nome);
            $nomeOriginal = $file->getClientOriginalName();
            if ($upload) {
                DB::transaction(function () use ($request, $caminho, $nome, $nomeOriginal, $certificadoprestador) {
                    $certificadoprestador->caminho      = $caminho . '/' . $nome;
                    $certificadoprestador->nome         = $nomeOriginal;
                    $certificadoprestador->data         = $request['data'];
                    $certificadoprestador->carga        = $request['carga'];
                    $certificadoprestador->save();
                });
                return response()->json('Upload de arquivo bem sucedido!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Erro, Upload não realizado!', 400)->header('Content-Type', 'text/plain');
            }
        } else {
            if ($file == null) {
                DB::transaction(function () use ($request, $certificadoprestador) {
                    $certificadoprestador->caminho = $request['caminho'];
                    $certificadoprestador->nome    = $request['nome'];
                    $certificadoprestador->data    = $request['data'];
                    $certificadoprestador->carga   = $request['carga'];
                    $certificadoprestador->save();
                });
                return response()->json('Dados salvos com sucesso!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Arquivo inválido ou corrompido!', 400)->header('Content-Type', 'text/plain');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Certificadoprestador  $certificadoprestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Certificadoprestador $certificadoprestador)
    {
        $certificadoprestador->ativo = false;
        $certificadoprestador->save();
    }
}
