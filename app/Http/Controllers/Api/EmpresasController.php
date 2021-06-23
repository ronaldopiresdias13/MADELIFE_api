<?php

namespace App\Http\Controllers\Api;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Escala;

class EmpresasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Empresa::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa = new Empresa();
        $empresa->razao = $request->razao;
        $empresa->cnpj  = $request->cnpj;
        $empresa->ie    = $request->ie;
        $empresa->logo  = $request->logo;
        $empresa->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, empresa $empresa)
    {
        $iten = $empresa;

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
        $empresa->ativo = false;
        $empresa->save();
    }
}
