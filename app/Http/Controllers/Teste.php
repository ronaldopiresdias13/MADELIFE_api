<?php

namespace App\Http\Controllers;

use App\Models\Escala;
use App\Models\OrdemservicoServico;
use App\Models\Servico;
use App\Models\Telefone;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        /* Preencher Tipos nas escalas que estão null */ // Não está funcionando
        // $escalas = Escala::where('tipo', null)->get();

        // foreach ($escalas as $key => $escala) {
        //     if ($escala->servico_id) {
        //         $tipo = OrdemservicoServico::where('ordemservico_id', $escala->ordemservico_id)->where('servico_id', $escala->servico_id)->first();
        //         dd($tipo);
        //     }
        //     // if ($escala->servico_id) {
        //     //     $servico = OrdemservicoServico::where('servico_id', $escala->servico_id)->where('descricao', '<>', null)->first();
        //     //     // $servico = Escala::where('servico_id', $escala->servico_id)->where('tipo', '<>', null)->first();
        //     //     dd($servico);
        //     // }
        // }
        // return $escalas;

        /* Preencher prestador_proprietario da tabela escalas */
        // $escalas = Escala::where('prestador_proprietario', null)->get();
        // foreach ($escalas as $key => $escala) {
        //     if ($escala->substituto) {
        //         $escala->prestador_proprietario = $escala->substituto;
        //         $escala->folga = true;
        //     } else {
        //         $escala->prestador_proprietario = $escala->prestador_id;
        //     }
        //     $escala->save();
        // }
        // return $escalas;

        /* Desativar Contratos que estão com OS desativadas */
        // $contratos = Orcamento::with('ordemservico')
        // ->whereHas('ordemservico', function (Builder $builder) {
        //     $builder->where('status', false);
        // })
        // ->where('status', true)
        // ->get();
        // foreach ($contratos as $key => $contrato) {
        //     $contrato->status = false;
        //     $contrato->save();
        // }
        // return $contratos;

        /* Zerar preços de Custo e Venda dos produtos de uma empresa */
        // $empresa_id = 1;
        // $produtos = Produto::where(function ($q) use ($empresa_id) {
        //     $q->where('empresa_id', $empresa_id)->where('valorcusto', '<>', 0);
        // })->orWhere(function ($q) use ($empresa_id) {
        //     $q->where('empresa_id', $empresa_id)->where('valorvenda', '<>', 0);
        // })
        // ->get();
        // foreach ($produtos as $key => $produto) {
        //     $produto->valorcusto = 0;
        //     $produto->valorvenda = 0;
        //     $produto->save();
        // }
        // return $produtos;

        /* Preencher Descricao da tabela OrcamentoServico */
        // $servicos = OrcamentoServico::where('descricao', null)
        // ->get();
        // foreach ($servicos as $key => $servico) {
        //     $servico->descricao = $servico->servico->descricao;
        //     $servico->save();
        // }
        // return $servicos;

        /* Remover mascara de todos os telefones */
        $telefones = Telefone::all();
        foreach ($telefones as $key => $telefone) {
            var_dump($telefone->telefone . ' => ');
            $car = array("(", ")", " ", "-");
            $telefone->telefone = str_replace($car, "", $telefone->telefone);
            var_dump($telefone->telefone . '\n');
            $telefone->save();
        }
        return $telefones;


        // $empresa_id = 1;
        // $prestador_id = 16;

        // $prestadores = Prestador::with(
        //     [
        //         'ordemservicos.servico',
        //         'ordemservicos.ordemservico.orcamento.homecare.paciente.pessoa'
        //     ])
        //     ->where('ativo', true)
        //     ->whereHas('ordemservicos.ordemservico', function (Builder $builder) use ($empresa_id) {
        //         $builder->where('empresa_id', $empresa_id);
        //     })
        //     ->find($prestador_id);

        // return $prestadores;


        // $escalas = Escala::where('assinaturaprestador', null)
        // ->whereBetween('dataentrada', ['2021-07-01', '2021-07-31'])
        // // ->orderByDesc('dataentrada')
        // ->where('ativo', 1)
        // ->where('status', 1)
        // ->limit(150)
        // ->get();
        // // $escala->ativo = $request->ativo;
        // // $escala->save();

        // foreach ($escalas as $key => $escala) {
        //     $assinada = Escala::where('assinaturaprestador', '<>', null)
        //     // ->whereBetween('dataentrada', ['2021-01-01', '2021-07-31'])
        //     ->where('ativo', 1)
        //     ->where('status', 1)
        //     ->where('prestador_id', $escala['prestador_id'])
        //     // ->where('prestador_id', $escalas[0]['prestador_id'])
        //     ->orderByDesc('dataentrada')
        //     ->first();

        //     var_dump($escala->id . ' ');

        //     if ($assinada) {
        //         $escala->assinaturaprestador = $assinada->assinaturaprestador;
        //         $escala->save();
        //     }
        // }

        // // return $assinada;
        // return $escalas;
    }
}
