<?php

namespace App\Http\Controllers\Web\Tiss;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Medicao;
use App\Services\TissService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TissController extends Controller
{
    public function gerarXml(Medicao $medicao)
    {

        $medicao = Medicao::with(
            'medicao_produtos.produto',
            'medicao_servicos.servico',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        )->find($medicao->id);

        $empresa = Empresa::find($medicao->empresa_id);

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
