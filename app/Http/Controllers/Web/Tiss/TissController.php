<?php

namespace App\Http\Controllers\Web\Tiss;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Medicao;
use App\Models\Profissional;
use App\Services\TissService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TissController extends Controller
{
    public function gerarXml(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $medicao = Medicao::with(
            'medicao_produtos.produto',
            'medicao_servicos.servico',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        )
            // ->whereIn('id', [316])
            //     ->get();
            ->find($request->medicoes);

        $empresa = Empresa::find($empresa_id);
        // return $medicao;
        // return str_replace('_', '.', $medicao->cliente->versaoTiss);

        if ($medicao->cliente->versaoTiss) {
            $func = 'gerar_xml_' . $medicao->cliente->versaoTiss;
            $tissService = new TissService($medicao, $empresa);
            $resposta = $tissService->$func();

            if ($resposta) {
                // return $resposta;
                return response()->json(['tiss' => $resposta], 200)->header('Content-Type', 'application/xml');
            } else {
                throw ValidationException::withMessages([
                    'tiss' => ['Erro ao gerar o TISS. Verifique se todos os dados estão corretos'],
                ]);
            }
        } else {
            return response()->json('Erro!\nVersão do TISS não informado no cadastro do Cliente!', 400)->header('Content-Type', 'text/plain');
        }
    }
}
