<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orcamento extends Model
{
    protected $guarded = [];

    public function historicos()
    {
        return $this->hasMany('App\Historicoorcamento')->where('ativo', true);
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function cidade()
    {
        return $this->belongsTo('App\Cidade');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }

    public function orcamento_servicos()
    {
        return $this->hasMany('App\OrcamentoServico')->where('ativo', true);
    }

    public function servicos()
    {
        return $this->belongsToMany('App\Servico', 'orcamento_servico')
            ->withPivot(
                'quantidade',
                'frequencia',
                'basecobranca',
                'valorunitario',
                'custo',
                'subtotal',
                'subtotalcusto',
                'adicionalnoturno',
                'icms',
                'inss',
                'iss',
                'valorcustomensal',
                'valorresultadomensal'
            )->wherePivot('ativo', true);
    }

    public function orcamento_produtos()
    {
        return $this->hasMany('App\OrcamentoProduto')->where('ativo', true);
    }

    public function produtos()
    {
        return $this->belongsToMany('App\Produto', 'orcamento_produto')
            ->withPivot(
                "quantidade",
                "valorunitario",
                "custo",
                "subtotal",
                "subtotalcusto",
                // "icms",
                // "inss",
                // "iss",
                "valorcustomensal",
                "valorresultadomensal"
            )->where('ativo', true);
    }

    public function orcamentocustos()
    {
        return $this->hasMany('App\Orcamentocusto')->where('ativo', true);
    }

    public function homecare()
    {
        return $this->hasOne('App\Homecare');
    }

    public function remocao()
    {
        return $this->hasOne('App\Remocao');
    }

    public function aph()
    {
        return $this->hasOne('App\Aph');
    }

    public function evento()
    {
        return $this->hasOne('App\Evento');
    }

    public function ordemServico()
    {
        return $this->hasOne('App\OrdemServico');
    }
}
