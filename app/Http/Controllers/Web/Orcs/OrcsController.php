<?php

namespace App\Http\Controllers\Web\Orcs;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrcResource;
use App\Models\Orc;
use App\Models\Orccusto;
use App\Models\OrcProduto;
use App\Models\OrcServico;
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
                'cliente',
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
        DB::transaction(function () use ($request) {
            $empresa_id = $request->user()->pessoa->profissional->empresa_id;
            if (!$empresa_id) {
                return 'Error';
            }

            $orc = new Orc();
            $orc->fill([
                "empresa_id"               => $empresa_id,
                "cliente_id"               => $request->cliente_id,
                "numero"                   => $request->numero,
                "tipo"                     => $request->tipo,
                "data"                     => $request->data,
                "quantidade"               => $request->quantidade,
                "unidade"                  => $request->unidade,
                "cidade_id"                => $request->cidade_id,
                "processo"                 => $request->processo,
                "situacao"                 => $request->situacao,
                "descricao"                => $request->descricao,
                "valortotalproduto"        => $request->valortotalproduto,
                "valortotalcusto"          => $request->valortotalcusto,
                "valortotalservico"        => $request->valortotalservico,
                "observacao"               => $request->observacao,
                "status"                   => $request->status,
                "venda_realizada"          => $request->venda_realizada,
                "venda_data"               => $request->venda_data,
                "homecare_paciente_id"     => $request->homecare_paciente_id,
                "aph_descricao"            => $request->aph_descricao,
                "aph_endereco"             => $request->aph_endereco,
                "aph_cep"                  => $request->aph_cep,
                "aph_cidade_id"            => $request->aph_cidade_id,
                "evento_nome"              => $request->evento_nome,
                "evento_endereco"          => $request->evento_endereco,
                "evento_cep"               => $request->evento_cep,
                "evento_cidade_id"         => $request->evento_cidade_id,
                "remocao_nome"             => $request->remocao_nome,
                "remocao_sexo"             => $request->remocao_sexo,
                "remocao_nascimento"       => $request->remocao_nascimento,
                "remocao_cpfcnpj"          => $request->remocao_cpfcnpj,
                "remocao_rgie"             => $request->remocao_rgie,
                "remocao_enderecoorigem"   => $request->remocao_enderecoorigem,
                "remocao_cidadeorigem_id"  => $request->remocao_cidadeorigem_id,
                "remocao_enderecodestino"  => $request->remocao_enderecodestino,
                "remocao_cidadedestino_id" => $request->remocao_cidadedestino_id,
                "remocao_observacao"       => $request->remocao_observacao,
            ])->save();

            foreach ($request->produtos as $item) {
                $orcProduto = new OrcProduto();
                $orcProduto->fill([
                    "orc_id"               => $orc->id,
                    "produto_id"           => $item['produto_id'],
                    "quantidade"           => $item['quantidade'],
                    "valorunitario"        => $item['valorunitario'],
                    "subtotal"             => $item['subtotal'],
                    "custo"                => $item['custo'],
                    "subtotalcusto"        => $item['subtotalcusto'],
                    "valorresultadomensal" => $item['valorresultadomensal'],
                    "valorcustomensal"     => $item['valorcustomensal'],
                    "locacao"              => $item['locacao'],
                    "descricao"            => $item['descricao'],
                ])->save();
            }

            foreach ($request->servicos as $item) {
                $orcServico = new OrcServico();
                $orcServico->fill([
                    "orc_id"               => $orc->id,
                    "servico_id"           => $item['servico_id'],
                    "quantidade"           => $item['quantidade'],
                    "basecobranca"         => $item['basecobranca'],
                    "frequencia"           => $item['frequencia'],
                    "valorunitario"        => $item['valorunitario'],
                    "subtotal"             => $item['subtotal'],
                    "custo"                => $item['custo'],
                    "custodiurno"          => $item['custodiurno'],
                    "custonoturno"         => $item['custonoturno'],
                    "subtotalcusto"        => $item['subtotalcusto'],
                    "valorresultadomensal" => $item['valorresultadomensal'],
                    "valorcustomensal"     => $item['valorcustomensal'],
                    "icms"                 => $item['icms'],
                    "iss"                  => $item['iss'],
                    "inss"                 => $item['inss'],
                    "descricao"            => $item['descricao'],
                ])->save();
            }

            foreach ($request->custos as $item) {
                $custo = new Orccusto();
                $custo->fill([
                    "orc_id"        => $orc->id,
                    "descricao"     => $item['descricao'],
                    "quantidade"    => $item['quantidade'],
                    "unidade"       => $item['unidade'],
                    "valorunitario" => $item['valorunitario'],
                    "valortotal"    => $item['valortotal'],
                ])->save();
            }
        });
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
        //
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
