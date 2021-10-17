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
        // if ($request['documentos']) {
        //     foreach ($request['documentos'] as $documento) {
        //         $md5 = md5_file($documento['anexo']['file']);
        //         $caminho = 'anexos/prestadores/';
        //         $nome = $md5 . '.' . explode(';', explode('/', $documento['anexo']['file'])[1])[0];
        //         $file = explode(',', $documento['anexo']['file'])[1];
        //         Storage::put($caminho . $nome, base64_decode($file));
        //         Anexo::create([
        //             'anexo_id' => $request->anexo_id,
        //             'anexo_type' => $request->anexo_type,
        //             'caminho' => $caminho . $nome,
        //             'nome'  => $documento['anexo']['name'],
        //             'descricao'  => $documento['descricao']
        //         ]);
        //     }
        // }

        if ($request['anexos']) {
            foreach ($request['anexos'] as $anexo) {
                $md5 = md5_file($anexo['file']);
                $caminho = 'anexos/';
                $nome = $md5 . '.' . explode(';', explode('/', $anexo['file'])[1])[0];
                $file = explode(',', $anexo['file'])[1];
                Storage::put($caminho . $nome, base64_decode($file));
                Anexo::create([
                    'anexo_id'   => $request->anexo_id,
                    'anexo_type' => $request->anexo_type,
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

    public function upload(Request $request)
    {
        // $data = [
        // $request['file']->allFiles();
        // ];
        // return response()->json($request);
        // return $request;
        $file = $request->file('file');
        // $file1 = $file[1]['file'];
        // $file2 = $file[2]['file'];
        // $caminho = 'anexos/' ;
        // $md5 = md5_file($file1);
        // $nome = $md5 . '.' . $file1->extension();
        // $upload = $file1->storeAs($caminho, $nome);
        // return "ok";
        $request = json_decode($request->data, true);
        if ($file && $file->isValid()) {
            $md5 = md5_file($file);
            $caminho = 'anexos/';
            $nome = $md5 . '.' . $file->extension();
            $upload = $file->storeAs($caminho, $nome);
            $nomeOriginal = $file->getClientOriginalName();
            if ($upload) {
                DB::transaction(function () use ($request, $nomeOriginal, $caminho, $nome) {
                    Anexo::create(
                        [
                            'anexo_type' => $request['anexo_type'],
                            // 'anexo_id'   => $request['anexo_id'],
                            'nome'       => $nomeOriginal,
                            'caminho'    => $caminho . '/' . $nome,
                            // 'descricao'  => $request['descricao']
                        ]
                    );
                });
                return response()->json('Upload de arquivo bem sucedido!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Erro, Upload não realizado!', 400)->header('Content-Type', 'text/plain');
            }
        }
    }
    public function download(Anexo $anexo)
    {
        $headers = [
            'Content-type' => 'text/txt',
        ];
        return response()->download(storage_path('app/public') . '/' . $anexo->caminho, $anexo->nome, $headers);
    }
}
