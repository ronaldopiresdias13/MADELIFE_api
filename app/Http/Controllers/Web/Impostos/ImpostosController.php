<?php

namespace App\Http\Controllers\Web\Impostos;

use App\Http\Controllers\Controller;
use App\Models\Imposto;
use Illuminate\Http\Request;

class ImpostosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        return Imposto::where('empresa_id', $empresa_id)->where('ativo', true)->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Imposto::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Imposto  $imposto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Imposto $imposto)
    {
        $iten = $imposto;

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
     * @param  \App\Imposto  $imposto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Imposto $imposto)
    {
        $imposto->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Imposto  $imposto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Imposto $imposto)
    {
        $imposto->ativo = false;
        $imposto->save();
    }
}
