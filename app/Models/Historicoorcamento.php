<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historicoorcamento extends Model
{
    protected $guarded = [];

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function orcamentoservicos()
    {
        return $this->belongsToMany(Orcamentoservico::class, 'historico_orcamento_servico')->wherePivot('ativo', true);
    }

    public function orcamentoprodutos()
    {
        return $this->belongsToMany(Orcamentoproduto::class, 'historico_orcamento_produto')->wherePivot('ativo', true);
    }
}
