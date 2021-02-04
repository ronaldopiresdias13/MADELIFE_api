<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pagamentointerno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagamentointernosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $empresa_id = null;

        if (Auth::check()) {
            if (Auth::user()->pessoa->profissional) {
                $empresa_id = Auth::user()->pessoa->profissional->empresa_id;
            }
        }

        return Pagamentointerno::where('empresa_id', $empresa_id)
            ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $empresa_id = null;

        if (Auth::check()) {
            if (Auth::user()->pessoa->profissional) {
                $empresa_id = Auth::user()->pessoa->profissional->empresa_id;
            }
        }

        Pagamentointerno::create(
            [
                'empresa_id' => $empresa_id,
                'pessoa_id'  => $request['pessoa_id'],
                'datainicio' => $request['datainicio'],
                'datafim'    => $request['datafim'],
                'salario'    => $request['salario'],
                'proventos'  => $request['proventos'],
                'descontos'  => $request['descontos']
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createlist(Request $request)
    {
        // return $request[0];

        $empresa_id = null;

        if (Auth::check()) {
            if (Auth::user()->pessoa->profissional) {
                $empresa_id = Auth::user()->pessoa->profissional->empresa_id;
            }
        }

        DB::transaction(function () use ($request, $empresa_id) {
            foreach ($request['pagamentos'] as $key => $item) {
                Pagamentointerno::create(
                    [
                        'empresa_id' => $empresa_id,
                        'pessoa_id'  => $item['pessoa_id'],
                        'datainicio' => $item['datainicio'],
                        'datafim'    => $item['datafim'],
                        'salario'    => $item['salario'],
                        'proventos'  => $item['proventos'],
                        'descontos'  => $item['descontos']
                    ]
                );
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pagamentointerno  $pagamentointerno
     * @return \Illuminate\Http\Response
     */
    public function show(Pagamentointerno $pagamentointerno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pagamentointerno  $pagamentointerno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pagamentointerno $pagamentointerno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pagamentointerno  $pagamentointerno
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pagamentointerno $pagamentointerno)
    {
        $pagamentointerno->delete();
    }
}
