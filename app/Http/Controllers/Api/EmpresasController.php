<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\empresa;
use Illuminate\Http\Request;

class EmpresasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $empresas = Empresa::all();
        // foreach ($empresas as $e){
        //     $clientes = $e->clientes;
        //     foreach ($clientes as $c) {
        //         $c->pessoa;
        //     }
        // }
        // return $empresas;
        $itens = new Empresa;
        
        if ($request->where) {
            foreach ($request->where as $key => $where) {
                $itens->where(
                    ($where['coluna'   ])? $where['coluna'   ] : 'id',
                    ($where['expressao'])? $where['expressao'] : 'like',
                    ($where['valor'    ])? $where['valor'    ] : '%'
                );
            }
        }

        if ($request->order) {
            foreach ($request->order as $key => $order) {
                $itens->orderBy(
                    ($order['coluna'])? $order['coluna'] : 'id',
                    ($order['tipo'  ])? $order['tipo'  ] : 'asc'
                );
            }
        }
        
        $itens = $itens->get();
        
        if ($request->adicionais) {
            foreach ($itens as $key => $iten) {
                foreach ($request->adicionais as $key => $adic) {
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
        // $empresa = Empresa::updateOrCreate(
        //     ['cnpj'  => $request->cnpj ],
        //     ['razao' => $request->razao, 'ie' => ]
        // );

        // $flight = App\Flight::firstOrCreate(
        //     ['name' => 'Flight 10'],
        //     ['delayed' => 1, 'arrival_time' => '11:30']
        // );

        $empresa = new Empresa;
        $empresa->razao = $request->razao;
        $empresa->cnpj  = $request->cnpj ;
        $empresa->ie    = $request->ie   ;
        $empresa->logo  = $request->logo ;
        $empresa->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(empresa $empresa)
    {
        return $empresa;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, empresa $empresa)
    {
        $empresa->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(empresa $empresa)
    {
        $empresa->delete();
    }
}
