<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unidademedida;
use Illuminate\Http\Request;

class UnidademedidasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Unidademedida::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $unidademedida = new Unidademedida();
        // $unidademedida->empresa_id = $request->empresa_id;
        $unidademedida->empresa_id = $request->empresa_id;
        $unidademedida->descricao = $request->descricao;
        $unidademedida->sigla = $request->sigla;
        $unidademedida->grupo = $request->grupo;
        $unidademedida->padrao = $request->padrao;
        // $unidademedida->status = $request->status;
        $unidademedida->status = 1;
        $unidademedida->sigla = $request->sigla;
        $unidademedida->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Unidademedida $unidademedida)
    {
        $iten = $unidademedida;

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
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unidademedida $unidademedida)
    {
        $unidademedida->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidademedida $unidademedida)
    {
        $unidademedida->ativo = false;
        $unidademedida->save();
    }
}
