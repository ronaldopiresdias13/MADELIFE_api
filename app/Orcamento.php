<?php

namespace App;

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

    public function orcamento_servicos()
    {
        return $this->hasMany(OrcamentoServico::class)->where('ativo', true);
    }

    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'orcamento_servico')
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
                'valorresultadomensal',
                'descricao'
            )->wherePivot('ativo', true);
    }

    public function orcamento_produtos()
    {
        return $this->hasMany(OrcamentoProduto::class)->where('ativo', true);
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'orcamento_produto')
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
                "valorresultadomensal",
                "descricao"
            )->wherePivot('ativo', true);
    }

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

    public function ordemServico()
    {
        return $this->hasOne(OrdemServico::class);
    }
}
