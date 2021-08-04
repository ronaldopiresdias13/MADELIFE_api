<?php

namespace App\Http\Controllers\Api;

use App\Models\EmpresaPrestador;
use App\Models\Empresa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmpresaPrestadorController extends Controller
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
            $itens = EmpresaPrestador::with($with)->where('ativo', true);
        } else {
            $itens = EmpresaPrestador::where('ativo', true);
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
        // $file = $request->file('file');
        // $request = json_decode($request->data, true);
        // if ($file && $file->isValid()) {
        //     $md5 = md5_file($file);
        //     $caminho = 'contratosPrestadores/' . $request['prestador_id'];
        //     $nome = $md5 . '.' . $file->extension();
        //     $upload = $file->storeAs($caminho, $nome);
        //     $nomeOriginal = $file->getClientOriginalName();
        //     if ($upload) {
        //         DB::transaction(function () use ($request, $caminho, $nome, $nomeOriginal) {
        //             $empresa_prestador = EmpresaPrestador::create([
        //                 'empresa_id'   => $request['empresa_id'],
        //                 'prestador_id' => $request['prestador_id'],
        //                 'contrato'     => $caminho . '/' . $nome,
        //                 'nome'         => $nomeOriginal,
        //                 'dataInicio'   => $request['dataInicio'],
        //                 'dataFim'      => $request['dataFim'],
        //                 'status'       => $request['status'],
        //                 'forma_contratacao' => $request['forma_contratacao']
        //             ]);
        //         });
        //         return response()->json('Upload de arquivo bem sucedido!', 200)->header('Content-Type', 'text/plain');
        //     } else {
        //         return response()->json('Erro, Upload não realizado!', 400)->header('Content-Type', 'text/plain');
        //     }
        // } else {
        //     if ($file == null) {
        //         DB::transaction(function () use ($request) {
        //             $empresa_prestador = EmpresaPrestador::create([
        //                 'empresa_id'   => $request['empresa_id'],
        //                 'prestador_id' => $request['prestador_id'],
        //                 'contrato'     => '',
        //                 'nome'         => '',
        //                 'dataInicio'   => $request['dataInicio'],
        //                 'dataFim'      => $request['dataFim'],
        //                 'status'       => $request['status'],
        //             ]);
        //         });
        //         return response()->json('Dados salvos com sucesso!', 200)->header('Content-Type', 'text/plain');
        //     } else {
        //         return response()->json('Arquivo inválido ou corrompido!', 400)->header('Content-Type', 'text/plain');
        //     }
        // }



        // DB::transaction(function () use ($request) {
        //     EmpresaPrestador::create($request->all());
        // });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmpresaPrestador  $empresaPrestador
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, EmpresaPrestador $empresaPrestador)
    {
        $iten = $empresaPrestador;

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
     * @param  \App\EmpresaPrestador  $empresaPrestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpresaPrestador $empresaPrestador)
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
                DB::transaction(function () use ($request, $caminho, $nome, $nomeOriginal, $empresaPrestador) {
                    $empresaPrestador->contrato   = $caminho . '/' . $nome;
                    $empresaPrestador->nome       = $nomeOriginal;
                    $empresaPrestador->dataInicio = $request['dataInicio'];
                    $empresaPrestador->dataFim    = $request['dataFim'];
                    $empresaPrestador->status     = $request['status'];
                    $empresaPrestador->save();
                });
                return response()->json('Upload de arquivo bem sucedido!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Erro, Upload não realizado!', 400)->header('Content-Type', 'text/plain');
            }
        } else {
            if ($file == null) {
                DB::transaction(function () use ($request, $empresaPrestador) {
                    $empresaPrestador->contrato   = $request['contrato'];
                    $empresaPrestador->nome       = $request['nome'];
                    $empresaPrestador->dataInicio = $request['dataInicio'];
                    $empresaPrestador->dataFim    = $request['dataFim'];
                    $empresaPrestador->status     = $request['status'];
                    $empresaPrestador->save();
                });
                return response()->json('Dados salvos com sucesso!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Arquivo inválido ou corrompido!', 400)->header('Content-Type', 'text/plain');
            }
        }

        // DB::transaction(function () use ($request, $empresaPrestador) {
        //     $empresaPrestador->update($request->all());
        // });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmpresaPrestador  $empresaPrestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmpresaPrestador $empresaPrestador)
    {
        DB::transaction(function () use ($empresaPrestador) {
            $empresaPrestador->status = 'Desativado';
            $empresaPrestador->ativo = false;
            $empresaPrestador->save();
        });
    }

    /**
     * Download the specified resource from storage.
     *
     * @param  \App\EmpresaPrestador  $empresaPrestador
     * @return \Illuminate\Http\Response
     */
    public function downloadFile(EmpresaPrestador $empresaPrestador)
    {
        // return $empresaPrestador;
        if (!Storage::exists($empresaPrestador['contrato'])) {
            return response()
                ->json('Não foi possivel encontrar o arquivo desejado!', 404)
                ->header('Content-Type', 'text/plain');
        } else {
            $file = Storage::get($empresaPrestador['contrato']);
        }

        $response =  array(
            'nome' => $empresaPrestador['nome'],
            'file' => base64_encode($file)
        );

        return response()->json($response);
    }

    public function quantidadeempresaprestador(Empresa $empresa)
    {
        return EmpresaPrestador::where('empresa_id', $empresa['id'])->where('ativo', 1)->count();
    }

    public function listaPrestadoresPorEmpresaIdEStatus(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return EmpresaPrestador::with([
            'prestador.pessoa.conselhos',
            'prestador.formacoes',
            'prestador.pessoa.dadosbancario.banco',
            'prestador.pessoa.enderecos.cidade',
            'prestador.pessoa.telefones',
            'prestador.pessoa.emails',
            'prestador.ordemservicos'
        ])
            ->where('empresa_id', $empresa_id)
            ->where('status', $request['status'])
            ->get();
    }
}
