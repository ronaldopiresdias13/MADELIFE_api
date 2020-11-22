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
    public function listCategorias()
    {
        // $categorias = Categoriadocumento::with('categorias')->get();
        $categorias = Categoriadocumento::all();

        return $categorias;
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
                'categoria' => $request['categoria']
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
