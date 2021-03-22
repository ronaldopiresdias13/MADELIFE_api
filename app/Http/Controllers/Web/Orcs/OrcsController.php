<?php

namespace App\Http\Controllers\Web\Orcs;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrcResource;
use App\Models\Orc;
use App\Models\Orccusto;
use App\Models\OrcProduto;
use App\Services\OrcService;
// use App\Models\OrcServico;
use App\Services\OrcsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrcsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        if (!$empresa_id) {
            return 'error';
        }
        $result = Orc::with(
            [
                'cidade',
                'cliente.pessoa',
                'homecare_paciente.pessoa',
                'aph_cidade',
                'evento_cidade',
                'remocao_cidadeorigem',
                'remocao_cidadedestino',
                'produtos.produto',
                'servicos.servico',
                'custos'
            ],
        )
            ->where('empresa_id', $empresa_id);

        if ($request->filter_nome) {
            $result->whereHas('homecare_paciente.pessoa', function (Builder $query) use ($request) {
                $query->where('nome', 'like', '%' . $request->filter_nome . '%');
            });

            $result->orWhere(function ($query) use ($empresa_id, $request) {
                $query->where('empresa_id', $empresa_id)
                    ->where('remocao_nome', 'like', '%' . $request->filter_nome . '%');
            });
        }

        $result = $result->orderByDesc('id')->paginate($request['per_page'] ? $request['per_page'] : 15);

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
        $orcService = new OrcService($request);
        $resposta = $orcService->store();
        if ($resposta['status']) {
            return $resposta;
        } else {
            return 'Ocorreu um erro ao salvar orçamento!';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Orc $orc
     * @return \Illuminate\Http\Response
     */
    public function show(Orc $orc)
    {
        return Orc::with(
            [
                'cidade',
                'cliente.pessoa',
                'homecare_paciente.pessoa',
                'aph_cidade',
                'evento_cidade',
                'remocao_cidadeorigem',
                'remocao_cidadedestino',
                'produtos.produto',
                'servicos.servico',
                'custos'
            ],
        )->find($orc->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orc  $orc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orc $orc)
    {
        $orcService = new OrcService($request, $orc);
        $resposta = $orcService->update();
        if ($resposta['status']) {
            return $resposta;
        } else {
            return 'Ocorreu um erro ao salvar orçamento!';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orc  $orc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orc $orc)
    {
        //
    }
}
