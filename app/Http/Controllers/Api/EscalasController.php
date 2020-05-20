<?php

namespace App\Http\Controllers\Api;

use App\Escala;
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
                    $iten[$adic];
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
    public function show(Escala $escala)
    {
        return $escala;
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
            'prestador_id' => $request['prestador_id'],
            'servico_id' => $request['servico_id'],
            'horaentrada' => $request['horaentrada'],
            'horasaida' => $request['horasaida'],
            'dataentrada' => $request['dataentrada'],
            'datasaida' => $request['datasaida'],
            'periodo' => $request['periodo'],
            'assinaturaprestador' => $request['assinaturaprestador'],
            'assinaturaresonsavel' => $request['assinaturaresonsavel'],
            'observacao' => $request['observacao'],
            'status' => $request['status'],
            'folga' => $request['folga'],
            'substituto' => $request['substituto'],
        ])->id;
        if($request['checkin']!= null){
            $pontoentrada = Ponto::create([
                'empresa_id' => $request['empresa_id'],
                'escala_id' => $escala,
                'latitude' =>   $request['checkin']['latitude'],
                'longitude' =>  $request['checkin']['longitude'],
                'data' =>       $request['checkin']['data'],
                'hora' =>       $request['checkin']['hora'],
                'tipo' => 'Checkin',
                'observacao' => '',
                'status' => $request['ckeckin']['status'],
            ]);
        }
        if($request['checkout']!= null){
            $pontosaida = Ponto::create([
                'empresa_id' => $request['empresa_id'],
                'escala_id' => $escala,
                'latitude' =>   $request['Checkout']['latitude'],
                'longitude' =>  $request['Checkout']['longitude'],
                'data' =>       $request['Checkout']['data'],
                'hora' =>       $request['Checkout']['hora'],
                'tipo' => 'Checkout',
                'observacao' => '',
                'status' => $request['Checkout']['status'],
            ]);
        }
        if($request['cuidados']){
            foreach ($request['cuidado'] as $cuidado) {
                $cuidados_escalas = CuidadoEscala::firstOrCreate([
                    'cuidado_id' => Cuidado::firstOrCreate([
                            'codigo' => $cuidado['codigo'],
                        ],
                        [
                            'descricao' => $request['descricao'],
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
        if($request['itemEscalaMonitoramentos']){
            foreach ($request['cuidado'] as $monitor){
                $monitoramento = Monitoramentoescala::create([
                    'escala_id' =>  $escala,
                    'datahora'  =>  $monitor['datahora'],
                    'pa'  =>  $monitor['pa'],
                    'p'  =>  $monitor['p'],
                    't'  =>  $monitor['t'],
                    'fr'  =>  $monitor['fr'],
                    'sat'  =>  $monitor['sat'],
                    'criev'  =>  $monitor['criev'],
                    'ev'  =>  $monitor['ev'],
                    'dieta'  =>  $monitor['dieta'],
                    'cridieta'  =>  $monitor['cridieta'],
                    'criliquido'  =>  $monitor['criliquido'],
                    'liquido'  =>  $monitor['liquido'],
                    'cridiurese'  =>  $monitor['cridiurese'],
                    'diurese'  =>  $monitor['diurese'],
                    'evac'  =>  $monitor['evac'],
                    'crievac'  =>  $monitor['crievac'],
                    'crivomito'  =>  $monitor['crivomito'],
                    'vomito'  =>  $monitor['vomito'],
                    'asp'  =>  $monitor['asp'],
                    'decub'  =>  $monitor['decub'],
                    'curativo'  =>  $monitor['curativo'],
                    'fraldas'  =>  $monitor['fraldas'],
                    'sondas'    =>  $monitor['sondas'],
                    'dextro'    =>  $monitor['dextro'],
                    'o2'        =>  $monitor['o2'],
                    'observacao'=>  $monitor['observacao'],
                ]);
            }
        }
        foreach ($variable as $key => $value) {
            # code...
        }
    }
}
