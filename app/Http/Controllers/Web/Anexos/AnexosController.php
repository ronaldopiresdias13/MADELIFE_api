<?php

namespace App\Http\Controllers\Web\Anexos;

use App\Http\Controllers\Controller;
use App\Models\Anexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AnexosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request['anexos']) {
            foreach ($request['anexos'] as $anexo) {
                $md5 = md5_file($anexo['file']);
                $caminho = 'anexos/';
                $nome = $md5 . '.' . explode(';', explode('/', $anexo['file'])[1])[0];
                $file = explode(',', $anexo['file'])[1];
                Storage::put($caminho . $nome, base64_decode($file));
                Anexo::create([
                    'anexo_id'   => $anexo['anexo_id'],
                    'anexo_type' => $anexo['anexo_type'],
                    'caminho'    => $caminho . $nome,
                    'nome'       => $anexo['nome'],
                    'descricao'  => $anexo['descricao']
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anexo $anexo)
    {
        $anexo->delete();
    }

    public function download(Anexo $anexo)
    {
        $headers = [
            'Content-type' => 'text/txt',
        ];
        return response()->download(storage_path('app/public') . '/' . $anexo->caminho, $anexo->nome, $headers);
    }
}
