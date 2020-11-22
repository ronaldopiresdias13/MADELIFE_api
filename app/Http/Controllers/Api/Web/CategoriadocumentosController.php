<?php

namespace App\Http\Controllers\Api\Web;

use App\Categoriadocumento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriadocumentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listCategorias(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return Categoriadocumento::where('empresa_id', $empresa_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newCategoria(Request $request)
    {
        Categoriadocumento::updateOrCreate(
            [
                'categoria' => $request['categoria']
            ],
            [
                'empresa_id' => $request['empresa_id'],
                'ativo' => true
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categoriadocumento  $categoriadocumento
     * @return \Illuminate\Http\Response
     */
    public function show(Categoriadocumento $categoriadocumento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categoriadocumento  $categoriadocumento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoriadocumento $categoriadocumento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categoriadocumento  $categoriadocumento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoriadocumento $categoriadocumento)
    {
        //
    }
}
