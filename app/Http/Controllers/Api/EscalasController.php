<?php

namespace App\Http\Controllers\Api;

use App\Escala;
use App\Cuidado;
use App\CuidadoEscala;
use App\Relatorioescala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EscalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Escala();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Escala::where(
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
            $itens = Escala::where('id', 'like', '%')->limit(5);
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
        $escala = Escala::create([
            'empresa_id'            => $request->empresa_id,
            'ordemservico_id'       => $request->ordemservico_id,
            'prestador_id'          => $request->prestador_id,
            'servico_id'            => $request->servico_id,
            'horaentrada'           => $request->horaentrada,
            'horasaida'             => $request->horasaida,
            'dataentrada'           => $request->dataentrada,
            'datasaida'             => $request->datasaida,
            'periodo'               => $request->periodo,
            'assinaturaprestador'   => $request->assinaturaprestador,
            'assinaturaresponsavel' => $request->assinaturaresponsavel,
            'observacao'            => $request->observacao,
            'status'                => $request->status,
            'folga'                 => $request->folga,
            'substituto'            => $request->substituto
        ]);

        foreach ($request->cuidados as $key => $cuidado) {
            $cuidado_escala = CuidadoEscala::create([
                'escala_id'  => $escala->id,
                'cuidado_id' => Cuidado::find($cuidado['cuidado']['id'])->id,
                'data'       => $cuidado['data'],
                'hora'       => $cuidado['hora'],
                'status'     => $cuidado['status'],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Escala $escala)
    {
        // $users = DB::table('users')->select('cpfcnpj', 'email as user_email')->get();

        // $users = DB::table('users')
        // ->join('user_acesso', function ($join) {
        //     $join->on('users.id', '=', 'user_acesso.user_id');
        //         //  ->where('contacts.user_id', '>', 5);
        // })
        // ->join('acessos', function ($join){
        //     $join->on('acessos.id', '=', 'user_acesso.acesso_id');
        // })
        // ->get();

        // $escala = Escala::find($escala->id);

        // $escala = $escala->with('ordemservico')->with('orcamento');

        // $escala = $escala->get();

        // return $escala;

        // $dados = Escala::with('ordemservico')->where('id', 75)->get();

        // $dados = Escala::join('ordemservicos', function($query) {
        //     $query->on('ordemservicos.id', '=', 'escalas.ordemservico_id');
        //     // $query->where('veiculo.cor', '=', 'vermelho');
        // })
        // ->get();

        // Paciente  full
        // Escala    full
        // Prestador nome, formacoes, conselho
        // Pontos    full

        // $escalas = Escala::all()->take(2);
        // foreach ($escalas as $key => $escala) {
        //     $escala->ordemservico->orcamento->homecare;
        // }

        // $escalas = $escalas->where('id', 2);
        // $escalas->get();

        // $escala = Escala::find(6);
        // $escala->ordemservico->orcamento->homecare;
        // $escalas->ordemservico;

        // return $escalas;

        // $escalas = DB::table('escalas')
        //     ->join('ordemservicos', 'ordemservicos.id'      , '=', 'escalas.ordemservico_id'   )
        //     ->join('orcamentos'   , 'orcamentos.id'         , '=', 'ordemservicos.orcamento_id')
        //     ->join('homecares'    , 'homecares.orcamento_id', '=', 'orcamentos.id'             )
        //     // ->join('eventos'      , 'eventos.orcamento_id'  , '=', 'orcamentos.id'             )
        //     // ->join('remocoes'     , 'remocoes.orcamento_id' , '=', 'orcamentos.id'             )
        //     ->join('prestadores'  , 'prestadores.id'        , '=', 'escalas.prestador_id'      )
        //     ->select('escalas.*', 'orcamentos.*', 'prestadores.*')
        //     ->limit(100)
        //     ->get();
        // return $escalas;



        // $escalas = DB::table('escalas')->where('prestador_id', 25)
        //     ->join('ordemservicos', 'ordemservicos.id'      , '=', 'escalas.ordemservico_id'   )
        //     ->join('orcamentos'   , 'orcamentos.id'         , '=', 'ordemservicos.orcamento_id')
        //     ->join('homecares'    , 'homecares.orcamento_id', '=', 'orcamentos.id'             )
        //     ->select('homecares.nome')
        //     ->groupBy('homecares.nome')
        //     ->limit(100)
        //     ->get();
        // return $escalas;






        $iten = $escala;

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
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Escala $escala)
    {
        $escala->empresa_id           = $request->empresa_id;
        $escala->ordemservico_id      = $request->ordemservico_id;
        $escala->prestador_id         = $request->prestador_id;
        $escala->horaentrada          = $request->horaentrada;
        $escala->horasaida            = $request->horasaida;
        $escala->dataentrada          = $request->dataentrada;
        $escala->datasaida            = $request->datasaida;
        $escala->periodo              = $request->periodo;
        $escala->assinaturaprestador  = $request->assinaturaprestador;
        $escala->assinaturaresponsavel = $request->assinaturaresponsavel;
        $escala->observacao           = $request->observacao;
        $escala->status               = $request->status;
        $escala->folga                = $request->folga;
        $escala->substituto           = $request->substituto;
        $escala->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Escala $escala)
    {
        $escala->delete();
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Escala  $escala
    //  * @return \Illuminate\Http\Response
    //  */
    // public function uploadFile(Request $request, Escala $escala)
    // {
    //     $file = $request->file('file'); // ou $request->file;
    //     // $extension = $file->extension();
    //     // dd($file->getClientOriginalName());
    //     if ($file->isValid()) {
    //         $md5 = md5_file($file);
    //         // dd($md5);
    //         $caminho = 'relatorioescalas/' . $escala['id'];
    //         $nome = $md5 . '.' . $file->extension();
    //         $upload = $file->storeAs($caminho, $nome);
    //         $nomeOriginal = $file->getClientOriginalName();

    //         if ($upload) {
    //             DB::transaction(function () use ($escala, $caminho, $nome, $nomeOriginal) {
    //                 $relatorio_escala = Relatorioescala::create([
    //                     'escala_id' => $escala['id'],
    //                     'caminho'   => $caminho . $nome,
    //                     'nome'      => $nomeOriginal,
    //                 ]);
    //             });
    //             return response()->json(["message" => "Image Uploaded Succesfully"]);
    //         } else {
    //             return response()->json(["message" => "Erro"]);
    //         }
    //     } else {
    //         return response()->json(["message" => "Select image first."]);
    //     }
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  string  $caminho
    //  * @return \Illuminate\Http\Response
    //  */
    // public function downloadFile(string $caminho)
    // {
    //     $file = Storage::get($caminho['caminho']);
    //     return $file;
    // }
}
