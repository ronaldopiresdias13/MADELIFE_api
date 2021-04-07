<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\Categoriadocumento;
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
        Categoriadocumento::firstOrCreate(
            [
                'categoria' => $request['categoria'],
                'empresa_id' => $request['empresa_id']
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
        $categoriadocumento->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categoriadocumento  $categoriadocumento
     * @return \Illuminate\Http\Response
     */
    public function delete(Categoriadocumento $categoriadocumento)
    {
        $categoriadocumento->delete();
    }
}
