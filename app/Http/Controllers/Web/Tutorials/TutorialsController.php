<?php

namespace App\Http\Controllers\Web\Tutorials;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use App\Models\TutorialFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TutorialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = Tutorial::with('tutorial_files');

        if($request->nome)
        {
            $result->where('nome', 'like', '%' . $request->nome . '%');
        };
        $result = $result->paginate($request['per_page'] ? $request['per_page'] : 15);

        if (env("APP_ENV", 'production') == 'production') {
            return $result->withPath(str_replace('http:', 'https:', $result->path()));
        } else {
            return $result;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request){
            $tutorial = Tutorial::create([
                'nome' => $request['nome'],
                'descricao' => $request['descricao'],
                'tipo' => $request['tipo']
            ]);
            if ($request->tutorial_files) {
                foreach ($request->tutorial_files as $anexo) {
                    $md5 = md5_file($anexo['file']);
                    $caminho = 'tutoriais/';
                    $nome = $md5 . '.' . explode(';', explode('/', $anexo['file'])[1])[0];
                    $file = explode(',', $anexo['file'])[1];
                    Storage::put($caminho . $nome, base64_decode($file));
                    TutorialFile::create([
                        'tutorial_id'   => $tutorial->id,
                        'path'          => $caminho . $nome,
                        'name'          => $anexo['name'],
                        'type'          => $anexo['type'],
                        'size'          => $anexo['size']
                    ]);
                }
            }
        });
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tutorial  $tutorial
     * @return \Illuminate\Http\Response
     */
    public function show(Tutorial $tutorial)
    {
        $tutorial->tutorial_files;
        return $tutorial;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tutorial  $tutorial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tutorial $tutorial)
    {
        DB::transaction(function () use ($request, $tutorial){
            $tutorial->update([
                'nome' => $request['nome'],
                'descricao' => $request['descricao'],
                'tipo' => $request['tipo']
            ]);
            if ($request->tutorial_files) {
                foreach ($request->tutorial_files as $anexo) {
                    if($anexo['id']){
                        TutorialFile::updateOrCreate([
                            'id'            => $anexo['id'],
                            'tutorial_id'   => $tutorial->id,
                            'path'          => $anexo['path'],
                            'name'          => $anexo['name'],
                            'type'          => $anexo['type'],
                            'size'          => $anexo['size']
                        ]);
                    }else{
                        $md5 = md5_file($anexo['file']);
                        $caminho = 'tutoriais/';
                        $nome = $md5 . '.' . explode(';', explode('/', $anexo['file'])[1])[0];
                        $file = explode(',', $anexo['file'])[1];
                        Storage::put($caminho . $nome, base64_decode($file));
                        TutorialFile::create([
                            'tutorial_id'   => $tutorial->id,
                            'path'          => $caminho . $nome,
                            'name'          => $anexo['name'],
                            'type'          => $anexo['type'],
                            'size'          => $anexo['size']
                        ]);
                    }
                    
                }
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tutorial  $tutorial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tutorial $tutorial)
    {
        $tutorial->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tutorial  $tutorial
     * @return \Illuminate\Http\Response
     */
    public function destroyFile(TutorialFile $tutorialFile)
    {
        $tutorialFile->delete();
    }
}
