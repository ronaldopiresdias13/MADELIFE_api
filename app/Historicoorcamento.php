<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historicoorcamento extends Model
{
    protected $guarded = [];

    public function orcamento()
    {
        return $this->belongsTo('App\Orcamento');
    }

    public function orcamentoservicos()
    {
        return $this->belongsToMany('App\Orcamentoservico', 'historico_orcamento_servico');
    }

    public function orcamentoprodutos()
    {
        return $this->belongsToMany('App\Orcamentoproduto', 'historico_orcamento_produto');
    }
}
