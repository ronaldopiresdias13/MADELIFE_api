<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orcamento extends Model
{
    protected $guarded = [];

    public function historicos()
    {
        return $this->hasMany(Historicoorcamento::class)->where('ativo', true);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function servicos()
    {
        return $this->hasMany(OrcamentoServico::class)->where('ativo', true);
    }

    // public function servicos()
    // {
    //     return $this->belongsToMany(Servico::class, 'orcamento_servico')
    //         ->withPivot(
    //             'quantidade',
    //             'frequencia',
    //             'basecobranca',
    //             'valorunitario',
    //             'custo',
    //             'custodiurno',
    //             'custonoturno',
    //             'subtotal',
    //             'subtotalcusto',
    //             'adicionalnoturno',
    //             'horascuidado',
    //             'horascuidadodiurno',
    //             'horascuidadonoturno',
    //             'icms',
    //             'inss',
    //             'iss',
    //             'valorcustomensal',
    //             'valorresultadomensal',
    //             'descricao'
    //         )->wherePivot('ativo', true);
    // }

    public function produtos()
    {
        return $this->hasMany(OrcamentoProduto::class)->where('ativo', true);
    }

    // public function produtos()
    // {
    //     return $this->belongsToMany(Produto::class, 'orcamento_produto')
    //         ->withPivot(
    //             "quantidade",
    //             "valorunitario",
    //             "custo",
    //             "subtotal",
    //             "subtotalcusto",
    //             // "icms",
    //             // "inss",
    //             // "iss",
    //             "valorcustomensal",
    //             "valorresultadomensal",
    //             "descricao"
    //         )->wherePivot('ativo', true);
    // }

    public function orcamentocustos()
    {
        return $this->hasMany(Orcamentocusto::class)->where('ativo', true);
    }

    public function homecare()
    {
        return $this->hasOne(Homecare::class);
    }

    public function remocao()
    {
        return $this->hasOne(Remocao::class);
    }

    public function aph()
    {
        return $this->hasOne(Aph::class);
    }

    public function evento()
    {
        return $this->hasOne(Evento::class);
    }

    public function ordemservico()
    {
        return $this->hasOne(Ordemservico::class);
    }

    public function venda()
    {
        return $this->hasOne(Venda::class);
    }

    // protected static function booted()
    // {
    //     static::updated(function ($orcamento) {
    //         $ordemservico = Ordemservico::where('orcamento_id', $orcamento->id)->first();

    //         // if ($ordemservico) {
    //             foreach ($ordemservico->servicos as $key => $servico) {
    //                 OrdemservicoServico::find($servico->pivot->id)->delete();
    //                 // $servico->delete();
    //             }

    //             foreach ($orcamento->servicos as $key => $servico) {
    //                 OrdemservicoServico::create(
    //                     [
    //                         'ordemservico_id'  => $ordemservico->id,
    //                         'servico_id'       => $servico->id,
    //                         'descricao'        => $servico['pivot']['basecobranca'],
    //                         'valordiurno'      => $servico['pivot']['custodiurno'] ? $servico['pivot']['custodiurno'] : 0,
    //                         'valornoturno'     => $servico['pivot']['custonoturno'] ? $servico['pivot']['custonoturno'] : 0,
    //                     ]
    //                 );
    //             }
    //         // }
    //     });
    // }
}
