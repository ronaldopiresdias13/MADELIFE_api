<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $guarded = [];

    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }

    public function orcamentoservicos()
    {
        return $this->hasMany('App\Orcamentoservico')->where('ativo', true);
    }

    public function formacoes()
    {
        return $this->belongsToMany('App\Formacao', 'servico_formacao')->wherePivot('ativo', true);
    }
}
