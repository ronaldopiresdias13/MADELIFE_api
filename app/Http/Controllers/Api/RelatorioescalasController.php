<?php

namespace App\Http\Controllers\Api;

use App\Escala;
use App\Http\Controllers\Controller;
use App\Relatorioescala;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RelatorioescalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = Relatorioescala::where('ativo', true);

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                // if ($key == 0) {
                //     $itens = Relatorioescala::where(
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
            //     $itens = Relatorioescala::where('id', 'like', '%')->limit(5);
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
     * @param  \App\Escala  $escala
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Escala $escala)
    {
        $file = $request->file('file');
        if ($file->isValid()) {
            $md5 = md5_file($file);
            $caminho = 'relatorioescalas/' . $escala['id'];
            $nome = $md5 . '.' . $file->extension();
            $upload = $file->storeAs($caminho, $nome);
            $nomeOriginal = $file->getClientOriginalName();
            if ($upload) {
                DB::transaction(function () use ($escala, $caminho, $nome, $nomeOriginal) {
                    $relatorio_escala = Relatorioescala::create([
                        'escala_id' => $escala['id'],
                        'caminho'   => $caminho . '/' . $nome,
                        'nome'      => $nomeOriginal,
                    ]);
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
     * @param  \App\Relatorioescala  $relatorioescala
     * @return \Illuminate\Http\Response
     */
    public function show(Relatorioescala $relatorioescala)
    {
        $file = Storage::get($relatorioescala['caminho']);

        $response =  array(
            'nome' => $relatorioescala['nome'],
            'file' => base64_encode($file)
        );

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Relatorioescala  $relatorioescala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Relatorioescala $relatorioescala)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Relatorioescala  $relatorioescala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Relatorioescala $relatorioescala)
    {
        $relatorioescala->ativo = false;
        $relatorioescala->save();
    }
}
