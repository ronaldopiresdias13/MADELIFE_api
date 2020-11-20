<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $guarded = [];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function orcamentoservicos()
    {
        return $this->hasMany(Orcamentoservico::class)->where('ativo', true);
    }

    public function formacoes()
    {
        return $this->belongsToMany(Formacao::class, 'servico_formacao')->wherePivot('ativo', true);
    }

    public function servicoFormacao()
    {
        return $this->hasMany(ServicoFormacao::class)->where('ativo', true);
    }
}
