<?php

namespace App\Http\Controllers\Api;

use App\Escala;
use App\Cuidado;
use App\CuidadoEscala;
use App\Prestador;
use App\Relatorio;
use App\Ponto;
use App\Monitoramentoescala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EscalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
                foreach ($request['adicionais'] as $key => $adic) {
                    if (is_string($adic)) {
                        $iten[$adic];
                    } else {
                        switch (count($adic)) {
                            case 1:
                                $iten[$adic[0]];
                                break;
                            
                            case 2:
                                $iten[$adic[0]][$adic[1]];
                                break;
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
        $escala = new Escala;
        $escala->empresa_id = $request->empresa_id;
        $escala->ordemservico_id = $request->ordemservico_id;
        $escala->prestador_id = $request->prestador_id;
        $escala->horaentrada = $request->horaentrada;
        $escala->horasaida = $request->horasaida;
        $escala->dataentrada = $request->dataentrada;
        $escala->datasaida = $request->datasaida;
        $escala->periodo = $request->periodo;
        $escala->assinaturaprestador = $request->assinaturaprestador;
        $escala->assinaturaresponsavel = $request->assinaturaresponsavel;
        $escala->observacao = $request->observacao;
        $escala->status = $request->status;
        $escala->folga = $request->folga;
        $escala->substituto = $request->substituto;
        $escala->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Escala $escala)
    {
        $iten = $escala;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adic) {
                if (is_string($adic)) {
                    $iten[$adic];
                } else {
                    switch (count($adic)) {
                        case 1:
                            $iten[$adic[0]];
                            break;
                        
                        case 2:
                            $iten[$adic[0]][$adic[1]];
                            break;
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
        $escala->update($request->all());
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
            'assinaturaresonsavel' => '',
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
                if($request['cuidado']!= null){
                    $cuidados_escalas = CuidadoEscala::create([
                        'cuidado_id' => Cuidado::firstOrCreate([
                                'codigo' => $cuidado['cuidado']['codigo'],
                            ],
                            [
                                'descricao' => $request['cuidado']['descricao'],
                                'empresa_id' => 1,
                                'status' => true,
                            ])->id,
                        'escala_id' => $escala,
                        'data' => null,
                        'hora' => $cuidado['horario'],
                        'status' => $cuidado['status'],
                        ]);
                }
                
            }
            
        };
        if($request['escala']['itemEscalaMonitoramentos']){
            foreach ($request['escala']['itemEscalaMonitoramentos'] as $monitor){
                $monitoramento = Monitoramentoescala::create([
                    'escala_id' =>  $escala,
                    'datahora'  =>  $monitor['horario'],
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
                    'observacao'=>  $monitor['observacoes'],
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
