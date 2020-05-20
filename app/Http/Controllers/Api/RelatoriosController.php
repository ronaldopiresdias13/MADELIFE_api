<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Relatorio;
use Illuminate\Http\Request;

class RelatoriosController extends Controller
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
                    $itens = Relatorio::where(
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
            $itens = Relatorio::where('id', 'like', '%');
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
        $relatorio = new Relatorio;
        $relatorio->empresa_id = $request->empresa_id;
        $relatorio->escala_id = $request->escala_id;
        $relatorio->escala_id = $request->escala_id;
        $relatorio->datahora = $request->datahora;
        $relatorio->quadro = $request->quadro;
        $relatorio->tipo = $request->tipo;
        $relatorio->texto = $request->texto;
        $relatorio->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function show(Relatorio $relatorio)
    {
        return $relatorio;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Relatorio $relatorio)
    {
        $relatorio->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Relatorio $relatorio)
    {
        $relatorio->delete();
    }
}
