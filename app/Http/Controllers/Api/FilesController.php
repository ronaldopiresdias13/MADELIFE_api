<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function upload(Request $request)
    {
        // $file = Storage::get('relatorioescalas/1/3364692105f5e966456ce0f8fb742d8c.pdf');
        // $file = Storage::download('relatorioescalas/1/3364692105f5e966456ce0f8fb742d8c.pdf');
        // $file = Storage::url('relatorioescalas/1/3364692105f5e966456ce0f8fb742d8c.pdf');
        // $file = Storage::temporaryUrl(
        //     'relatorioescalas/1/3364692105f5e966456ce0f8fb742d8c.pdf',
        //     now()->addMinutes(5)
        // );

        // dd($file);

        $file = $request->file('file'); // ou $request->file;
        // $extension = $file->extension();
        if ($file->isValid()) {
            $md5 = md5_file($file);
            // dd($md5);
            $nome = $md5 . '.' . $file->extension();
            $upload = $file->storeAs('relatorioescalas/1', $nome);

            if ($upload) {
                return response()->json(["message" => "Image Uploaded Succesfully"]);
            } else {
                return response()->json(["message" => "Erro"]);
            }
        } else {
            return response()->json(["message" => "Select image first."]);
        }

        // return 'pronto';

        // 3364692105f5e966456ce0f8fb742d8c
        // 3364692105f5e966456ce0f8fb742d8c
        // 3364692105f5e966456ce0f8fb742d8c

        dd($request->file('image'));

        $file = $request->file('file');

        if ($request->hasFile('file')) {
            // $nome      = '1'.kebab_case('perfil'); // gera o nome da image
            $nome      = 'relatorioescalas/1/89654r029856298582895702985476098/nomefile.pdf'; // gera o nome da image
            $extencao  = $file->getClientOriginalExtension(); // pega a extenção da imagem
            $nomeImage = "{$nome}.{$extencao}";

            $upload = $file->storeAs('users', $nomeImage);

            if ($upload) {
                return response()->json(["message" => "Image Uploaded Succesfully"]);
            } else {
                return response()->json(["message" => "Erro"]);
            }
        } else {
            return response()->json(["message" => "Select image first."]);
        }
    }
}
