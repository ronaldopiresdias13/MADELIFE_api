<?php

namespace App\Services;

use App\Models\Orc;
use App\Models\Orccusto;
use App\Models\OrcProduto;
use App\Models\OrcServico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrcService
{
    protected $request = null;
    protected $orc = null;

    public function __construct(Request $request, Orc $orc = null)
    {
        $this->request = $request;
        $this->orc     = $orc;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $this->request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        DB::transaction(function() {
            $empresa_id = $this->request->user()->pessoa->profissional->empresa_id;
            if (!$empresa_id) {
                return 'Error';
            }

            $orc = new Orc();
            $orc->fill([
                "empresa_id"               => $empresa_id,
                "cliente_id"               => $this->request->cliente_id,
                "numero"                   => $this->request->numero,
                "tipo"                     => $this->request->tipo,
                "data"                     => $this->request->data,
                "quantidade"               => $this->request->quantidade,
                "unidade"                  => $this->request->unidade,
                "cidade_id"                => $this->request->cidade_id,
                "processo"                 => $this->request->processo,
                "situacao"                 => $this->request->situacao,
                "descricao"                => $this->request->descricao,
                "valortotalproduto"        => $this->request->valortotalproduto,
                "valortotalcusto"          => $this->request->valortotalcusto,
                "valortotalservico"        => $this->request->valortotalservico,
                "observacao"               => $this->request->observacao,
                "status"                   => $this->request->status,
                "venda_realizada"          => $this->request->venda_realizada,
                "venda_data"               => $this->request->venda_data,
                "homecare_paciente_id"     => $this->request->homecare_paciente_id,
                "aph_descricao"            => $this->request->aph_descricao,
                "aph_endereco"             => $this->request->aph_endereco,
                "aph_cep"                  => $this->request->aph_cep,
                "aph_cidade_id"            => $this->request->aph_cidade_id,
                "evento_nome"              => $this->request->evento_nome,
                "evento_endereco"          => $this->request->evento_endereco,
                "evento_cep"               => $this->request->evento_cep,
                "evento_cidade_id"         => $this->request->evento_cidade_id,
                "remocao_nome"             => $this->request->remocao_nome,
                "remocao_sexo"             => $this->request->remocao_sexo,
                "remocao_nascimento"       => $this->request->remocao_nascimento,
                "remocao_cpfcnpj"          => $this->request->remocao_cpfcnpj,
                "remocao_rgie"             => $this->request->remocao_rgie,
                "remocao_enderecoorigem"   => $this->request->remocao_enderecoorigem,
                "remocao_cidadeorigem_id"  => $this->request->remocao_cidadeorigem_id,
                "remocao_enderecodestino"  => $this->request->remocao_enderecodestino,
                "remocao_cidadedestino_id" => $this->request->remocao_cidadedestino_id,
                "remocao_observacao"       => $this->request->remocao_observacao,
            ])->save();

            foreach ($this->request->produtos as $item) {
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

            foreach ($this->request->servicos as $item) {
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

            foreach ($this->request->custos as $item) {
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
            return ['status' => true, 'orc' => $orc->id];
        });
    }

    public function update()
    {
        DB::transaction(function() {
            $this->orc->fill([
                "cliente_id"               => $this->request->cliente_id,
                "numero"                   => $this->request->numero,
                "tipo"                     => $this->request->tipo,
                "data"                     => $this->request->data,
                "quantidade"               => $this->request->quantidade,
                "unidade"                  => $this->request->unidade,
                "cidade_id"                => $this->request->cidade_id,
                "processo"                 => $this->request->processo,
                "situacao"                 => $this->request->situacao,
                "descricao"                => $this->request->descricao,
                "valortotalproduto"        => $this->request->valortotalproduto,
                "valortotalcusto"          => $this->request->valortotalcusto,
                "valortotalservico"        => $this->request->valortotalservico,
                "observacao"               => $this->request->observacao,
                "status"                   => $this->request->status,
                "venda_realizada"          => $this->request->venda_realizada,
                "venda_data"               => $this->request->venda_data,
                "homecare_paciente_id"     => $this->request->homecare_paciente_id,
                "aph_descricao"            => $this->request->aph_descricao,
                "aph_endereco"             => $this->request->aph_endereco,
                "aph_cep"                  => $this->request->aph_cep,
                "aph_cidade_id"            => $this->request->aph_cidade_id,
                "evento_nome"              => $this->request->evento_nome,
                "evento_endereco"          => $this->request->evento_endereco,
                "evento_cep"               => $this->request->evento_cep,
                "evento_cidade_id"         => $this->request->evento_cidade_id,
                "remocao_nome"             => $this->request->remocao_nome,
                "remocao_sexo"             => $this->request->remocao_sexo,
                "remocao_nascimento"       => $this->request->remocao_nascimento,
                "remocao_cpfcnpj"          => $this->request->remocao_cpfcnpj,
                "remocao_rgie"             => $this->request->remocao_rgie,
                "remocao_enderecoorigem"   => $this->request->remocao_enderecoorigem,
                "remocao_cidadeorigem_id"  => $this->request->remocao_cidadeorigem_id,
                "remocao_enderecodestino"  => $this->request->remocao_enderecodestino,
                "remocao_cidadedestino_id" => $this->request->remocao_cidadedestino_id,
                "remocao_observacao"       => $this->request->remocao_observacao,
            ])->save();

            $produtos = [];

            foreach ($this->request->produtos as $item) {
                $orcProduto = new OrcProduto();
                $orcProduto = [
                    "orc_id"               => $this->orc->id,
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
                ]; //->save();
                array_push($orcProduto, $produtos);
            }

            $this->orc->produtos()->sync($produtos);

            // foreach ($this->request->servicos as $item) {
            //     $orcServico = new OrcServico();
            //     $orcServico->fill([
            //         "orc_id"               => $this->orc->id,
            //         "servico_id"           => $item['servico_id'],
            //         "quantidade"           => $item['quantidade'],
            //         "basecobranca"         => $item['basecobranca'],
            //         "frequencia"           => $item['frequencia'],
            //         "valorunitario"        => $item['valorunitario'],
            //         "subtotal"             => $item['subtotal'],
            //         "custo"                => $item['custo'],
            //         "custodiurno"          => $item['custodiurno'],
            //         "custonoturno"         => $item['custonoturno'],
            //         "subtotalcusto"        => $item['subtotalcusto'],
            //         "valorresultadomensal" => $item['valorresultadomensal'],
            //         "valorcustomensal"     => $item['valorcustomensal'],
            //         "icms"                 => $item['icms'],
            //         "iss"                  => $item['iss'],
            //         "inss"                 => $item['inss'],
            //         "descricao"            => $item['descricao'],
            //     ])->save();
            // }

            // foreach ($this->request->custos as $item) {
            //     $custo = new Orccusto();
            //     $custo->fill([
            //         "orc_id"        => $this->orc->id,
            //         "descricao"     => $item['descricao'],
            //         "quantidade"    => $item['quantidade'],
            //         "unidade"       => $item['unidade'],
            //         "valorunitario" => $item['valorunitario'],
            //         "valortotal"    => $item['valortotal'],
            //     ])->save();
            // }
        });
    }
}
