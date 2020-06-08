<?php

namespace App\Http\Controllers\Api;

use App\Escala;
use App\Cuidado;
use App\CuidadoEscala;
use App\Prestador;
use App\Relatorio;
use App\Ponto;
use App\Monitoramentoescala;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EscalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = null;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }
        
        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Escala::where(
                        ($where['coluna'   ])? $where['coluna'   ] : 'id'  ,
                        ($where['expressao'])? $where['expressao'] : 'like',
                        ($where['valor'    ])? $where['valor'    ] : '%'
                    );
                } else {
                    $itens->where(
                        ($where['coluna'   ])? $where['coluna'   ] : 'id',
                        ($where['expressao'])? $where['expressao'] : 'like',
                        ($where['valor'    ])? $where['valor'    ] : '%'
                    );
                }
            }
        } else {
            $itens = Escala::where('id', 'like', '%');
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna'])? $order['coluna'] : 'id',
                    ($order['tipo'  ])? $order['tipo'  ] : 'asc'
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
                                }
                                else {
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
            'empresa_id'            => $request->empresa_id           ,
            'ordemservico_id'       => $request->ordemservico_id      ,
            'prestador_id'          => $request->prestador_id         ,
            'servico_id'            => $request->servico_id           ,
            'horaentrada'           => $request->horaentrada          ,
            'horasaida'             => $request->horasaida            ,
            'dataentrada'           => $request->dataentrada          ,
            'datasaida'             => $request->datasaida            ,
            'periodo'               => $request->periodo              ,
            'assinaturaprestador'   => $request->assinaturaprestador  ,
            'assinaturaresponsavel' => $request->assinaturaresponsavel,
            'observacao'            => $request->observacao           ,
            'status'                => $request->status               ,
            'folga'                 => $request->folga                ,
            'substituto'            => $request->substituto
        ]);

        foreach ($request->cuidados as $key => $cuidado) {
            $cuidado_escala = CuidadoEscala::create([
                'escala_id'  => $escala->id                             ,
                'cuidado_id' => Cuidado::find($cuidado->cuidado->id)->id,
                'data'       => $cuidado->data                          ,
                'hora'       => $cuidado->hora                          ,
                'status'     => $cuidado->status                        ,
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
                            }
                            else {
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

    public function migracao(Request $request){
        $escala = Escala::create([
            'empresa_id' => $request['empresa_id'],
            'ordemservico_id' => $request['ordemservico_id'],
            'prestador_id' => $request['prestadorId']['id'],
            'servico_id' => ($request['servico_id']) ? $request['servico_id']['id'] : null,
            'horaentrada' =>    $request['escala']['horaentrada'],
            'horasaida' =>      $request['escala']['horasaida'],
            'dataentrada' =>    $request['escala']['dataentrada'],
            'datasaida' =>      $request['escala']['datasaida'],
            'periodo' =>        $request['escala']['periodo'],
            'assinaturaprestador' => '',
            'assinaturaresponsavel' => '',
            'observacao' =>     $request['escala']['observacoes'],
            'status' =>         $request['escala']['status'],
            'folga' =>          $request['escala']['folga'],
            'substituto' =>     $request['escala']['substituto'],
        ])->id;
        if($request['escala']['checkin']!= null){
            $pontoentrada = Ponto::create([
                'empresa_id' => $request['empresa_id'],
                'escala_id' => $escala,
                'latitude' =>   $request['escala']['checkin']['latitude'],
                'longitude' =>  $request['escala']['checkin']['longitude'],
                'data' =>       $request['escala']['checkin']['data'],
                'hora' =>       $request['escala']['checkin']['hora'],
                'tipo' => 'Checkin',
                'observacao' => '',
                'status' => $request['escala']['checkin']['status'],
            ]);
        };
        if($request['escala']['checkout']!= null){
            $pontosaida = Ponto::create([
                'empresa_id' => $request['empresa_id'],
                'escala_id' => $escala,
                'latitude' =>   $request['escala']['checkout']['latitude'],
                'longitude' =>  $request['escala']['checkout']['longitude'],
                'data' =>       $request['escala']['checkout']['data'],
                'hora' =>       $request['escala']['checkout']['hora'],
                'tipo' => 'Checkout',
                'observacao' => '',
                'status' => $request['escala']['checkout']['status'],
            ]);
        };
        if($request['escala']['cuidados']){
            foreach ($request['escala']['cuidados'] as $cuidado) {
                    $cuidados_escalas = CuidadoEscala::create([
                        'cuidado_id' => Cuidado::firstOrCreate([
                                'codigo' => $cuidado['cuidado']['codigo'],
                            ],
                            [
                                'descricao' => $cuidado['cuidado']['descricao'],
                                'empresa_id' => 1,
                                'status' => true,
                            ])->id,
                        'escala_id' => $escala,
                        'data' => null,
                        'hora' => $cuidado['horario'],
                        'status' => $cuidado['status'],
                        ]);
                
            }
            
        };
        if($request['escala']['itemEscalaMonitoramentos']){
            foreach ($request['escala']['itemEscalaMonitoramentos'] as $monitor){
                $monitoramento = Monitoramentoescala::create([
                    'escala_id' =>  $escala,
                    'data'  =>  '',
                    'hora'  =>  $monitor['horario'],
                    'pa'  =>  $monitor['pa'],
                    'p'  =>  $monitor['p'],
                    't'  =>  $monitor['t'],
                    'fr'  =>  $monitor['fr'],
                    'sat'  =>  $monitor['sat'],
                    'criev'  =>  $monitor['criEv'],
                    'ev'  =>  $monitor['ev'],
                    'dieta'  =>  $monitor['dieta'],
                    'cridieta'  =>  $monitor['criDieta'],
                    'criliquido'  =>  $monitor['criLiq'],
                    'liquido'  =>  $monitor['liq'],
                    'cridiurese'  =>  $monitor['criDiurese'],
                    'diurese'  =>  $monitor['diurese'],
                    'evac'  =>  $monitor['evac'],
                    'crievac'  =>  $monitor['criEvac'],
                    'crivomito'  =>  $monitor['criVomito'],
                    'vomito'  =>  $monitor['vomito'],
                    'asp'  =>  $monitor['asp'],
                    'decub'  =>  $monitor['decub'],
                    'curativo'  =>  $monitor['curativo'],
                    'fraldas'  =>  $monitor['fraldas'],
                    'sondas'    =>  $monitor['sondas'],
                    'dextro'    =>  $monitor['dextro'],
                    'o2'        =>  $monitor['o2'],
                    'observacao'=>  '',
                ]);
            }
        }
        foreach ($request['relatorio'] as $relatorio) {
            $relatorio_escala = Relatorio::create([
                'escala_id' => $escala,
                'hora' => $relatorio['hora'],
                'data' => $relatorio['data'],
                'quadro' => $relatorio['quadro'],
                'tipo' => $relatorio['tipo'],
                'texto' => $relatorio['texto']
            ]);
        }
    }
}
