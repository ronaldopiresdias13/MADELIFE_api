<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;








    public function uploadimage(Request $request)
    {
        $file = $request->file('image');

        if ($request->hasFile('image')) {

            // $nome      = '1'.kebab_case('perfil'); // gera o nome da image
            $nome      = '1perfil'; // gera o nome da image
            $extencao  = $file->getClientOriginalExtension(); // pega a extenção da imagem
            $nomeImage = "{$nome}.{$extencao}";

            $upload = $file->storeAs('users', $nomeImage);

            if ($upload) {
                return response()->json(["message" => "Image Uploaded Succesfully"]);
            } else {
                return response()->json(["message" => "Erro"]);
            }
        }
        else {
            return response()->json(["message" => "Select image first."]);
        }
    }
}
