<?php

namespace App\Http\Controllers\Web\Tiss;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Medicao;
use App\Models\Tiss;
use App\Services\TissService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TissController extends Controller
{
    public function gerarXml(Request $request, Cliente $cliente)
    {
        $medicoes = Medicao::with(
            'medicao_produtos.produto',
            'medicao_servicos.servico',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        )
            ->whereIn('id', $request->medicoes)
            ->get();

        if ($cliente->versaoTiss) {
            $tissService = new TissService($medicoes, $cliente);
            $resposta = $tissService->criarXml();

            if ($resposta) {
                return response()->json('Ok! \nSalvo com Sucesso!', 200)->header('Content-Type', 'text/plain');
            } else {
                throw ValidationException::withMessages([
                    'tiss' => ['Erro ao gerar o TISS. Verifique se todos os dados estão corretos'],
                ]);
            }
        } else {
            return response()->json('Erro! \nVersão do TISS não informado no cadastro do Cliente!', 400)->header('Content-Type', 'text/plain');
        }
    }

    public function editarXml(Request $request, Cliente $cliente)
    {
        $medicoes = Medicao::with(
            'medicao_produtos.produto',
            'medicao_servicos.servico',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        )
            ->whereIn('id', $request->medicoes)
            ->get();

        if ($cliente->versaoTiss) {
            $tiss = $request['tiss_id'];
            $tissService = new TissService($medicoes, $cliente, $tiss);
            $resposta = $tissService->editarXml();

            if ($resposta) {
                $medicoes = Medicao::where('tiss_id', $tiss)
                    ->whereNotIn('id', $request->medicoes)
                    ->get();
                foreach ($medicoes as $key => $medicao) {
                    $medicao->tiss_id = null;
                    $medicao->save();
                }
                // return response()->json(['tiss' => $resposta], 200)->header('Content-Type', 'application/xml');
                return response()->json('Ok!\nSalvo com Sucesso!', 200)->header('Content-Type', 'text/plain');
            } else {
                throw ValidationException::withMessages([
                    'tiss' => ['Erro ao gerar o TISS. Verifique se todos os dados estão corretos'],
                ]);
            }
        } else {
            return response()->json('Erro!\nVersão do TISS não informado no cadastro do Cliente!', 400)->header('Content-Type', 'text/plain');
        }
    }

    public function gerarXmlPorCliente(Request $request, Cliente $cliente)
    {
        // return $request;
        // $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $medicao = Medicao::with(
            'medicao_produtos.produto',
            'medicao_servicos.servico',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        )
            ->whereIn('id', $request->medicoes)
            ->get();

        // return $medicao;
        // $empresa = Empresa::find($empresa_id);

        if ($cliente->versaoTiss) {
            $func = 'gerar_xml_' . $cliente->versaoTiss;
            // $tissService = new TissService($medicao, $empresa);
            $resposta = $this->$func($medicao, $cliente);

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = Tiss::join('clientes as c', 'c.id', '=', 'tiss.cliente_id')
            ->join('pessoas as p', 'p.id', '=', 'c.pessoa_id')
            ->with('cliente.pessoa', 'medicoes.ordemservico.orcamento.homecare.paciente.pessoa')->orderBy('p.nome');

        if ($request->cliente_id) {
            $result->whereHas('cliente', function (Builder $query) use ($request) {
                $query->where('id', $request->cliente_id);
            });
        }
        if ($request->nome) {
            $result->whereHas('medicoes.ordemservico.orcamento.homecare.paciente.pessoa', function (Builder $query) use ($request) {
                $query->where('nome', 'like', '%' . $request->nome . '%');
            });
        }

        if ($request->paginate) {
            $result = $result->paginate($request['per_page'] ? $request['per_page'] : 15);
            if (env("APP_ENV", 'production') == 'production') {
                return $result->withPath(str_replace('http:', 'https:', $result->path()));
            } else {
                return $result;
            }
        } else {
            return $result->get();
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
        $tiss = new Tiss();
        $tiss->fill([
            'cliente_id' => $request->cliente_id,
            'sequencia' => $request->sequencia,
            'caminhoxml' => $request->caminhoxml
        ]);
        $tiss->save();
    }
}
