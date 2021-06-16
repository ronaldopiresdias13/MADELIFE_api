<?php

namespace App\Http\Controllers\Web\Tiss;

use App\Http\Controllers\Controller;
use App\Models\Tiss;
use App\Services\TissService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TissController extends Controller
{
    public function gerarXml_3_05_00(string $versao, Request $request) {
        $tissService = new TissService($versao, $request);
        $resposta = $tissService->gerar_xml_3_05_00();

        if ($resposta['status'] == true) {
            return $resposta;
        } else {
            throw ValidationException::withMessages([
                'tiss' => ['Erro ao gerar o TISS. Verifique se todos os dados estão corretos'],
            ]);
        }
    }

    public function gerarXml(string $versao, Request $request) {

        $func = 'gerar_xml_' . $versao;

        $tissService = new TissService($versao, $request);
        $resposta = $tissService->$func();

        if ($resposta) {
            return $resposta;
        } else {
            throw ValidationException::withMessages([
                'tiss' => ['Erro ao gerar o TISS. Verifique se todos os dados estão corretos'],
            ]);
            // return 'erro';
        }
    }
}
