<?php

namespace App\Http\Controllers;

use App\Models\Contasbancaria;
use App\Models\Dadosbancario;
use App\Models\Escala;
use App\Models\Ordemservico;
use App\Models\OrdemservicoServico;
use App\Models\Servico;
use App\Models\Telefone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        $user = $request->user();
        $profissional = $user->pessoa->profissional;

        $escalas = Ordemservico::
        join('orcamentos','orcamentos.id','=','ordemservicos.orcamento_id')
        ->join('homecares','homecares.orcamento_id','=','ordemservicos.orcamento_id')
        ->join('pacientes','pacientes.id','=','homecares.paciente_id')
        ->join('pessoas','pessoas.id','=','pacientes.pessoa_id')

        ->with([
            // 'servicos',
            'acessos',
            'profissional.pessoa',
            'orcamento.cidade', 'orcamento' => function ($query) {
                $query->with(['servicos.servico', 'homecare' => function ($query) {
                    $query->with(['paciente.pessoa', 'paciente.responsavel.pessoa']);
                }]);
                $query->with(['cliente' => function ($query) {
                    $query->select('id', 'pessoa_id');
                    $query->with(['pessoa' => function ($query) {
                        $query->select('id', 'nome');
                    }]);
                }]);
            }
        ])
            ->whereHas('orcamento.cliente', function (Builder $query) use ($request) {
                $query->where('id', 'like', $request->cliente_id ? $request->cliente_id : '%');
            })
            ->whereHas('orcamento.cidade', function (Builder $query) use ($request) {
                $query->where('id', 'like', $request->cidade_id ? $request->cidade_id : '%');
            })
            ->whereHas('orcamento.homecare.paciente.pessoa', function (Builder $query) use ($request) {
                $query->where('nome', 'like', $request->nome ? $request->nome : '%');
            })
            ->whereHas('profissional', function (Builder $query) use ($request) {
                $query->where('id', 'like', $request->profissional_id ? $request->profissional_id : '%');
            })
            ->withCount('prestadores')
            ->withCount('escalas')
            ->where('ordemservicos.empresa_id', $profissional->empresa_id)
            ->where('ordemservicos.ativo', true)
            // ->limit(1)
            ->orderByDesc('pessoas.nome')

            // ->orderByDesc('orcamento.homecare.paciente.pessoa.nome')
            ->select(['ordemservicos.id', 'ordemservicos.orcamento_id', 'ordemservicos.profissional_id']);
        if ($request->paginate) {
            $escalas = $escalas->paginate($request['per_page'] ? $request['per_page'] : 15); //->sortBy('orcamento.homecare.paciente.pessoa.nome');
        } else {
            $escalas = $escalas->get();
        }

        if (env("APP_ENV", 'production') == 'production') {
            // return $escalas->withPath(str_replace('http:', 'https:', $escalas->path()));
            return $escalas;
        } else {
            return $escalas;
        }

        /*Pegar cpf de pessoa e adicionar nas contas bancarias que não tem cpf ou cnpj */
        // $dadosbancarios = Dadosbancario::where('cpfcnpj', null)
        //     ->orWhere(function ($query) {
        //         $query->where('cpfcnpj', '');
        //     })->get();
        // foreach ($dadosbancarios as $key => $dadosbancario) {

        //     $dadosbancario->cpfcnpj = $dadosbancario->pessoa->cpfcnpj;
        //     $dadosbancario->save();
        // }
        // return $dadosbancarios;

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
        // $telefones = Telefone::all();
        // foreach ($telefones as $key => $telefone) {
        //     var_dump($telefone->telefone . ' => ');
        //     $car = array("(", ")", " ", "-");
        //     $telefone->telefone = str_replace($car, "", $telefone->telefone);
        //     var_dump($telefone->telefone . '\n');
        //     $telefone->save();
        // }
        // return $telefones;


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
