<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestador extends Model
{
    protected $table = 'prestadores';
    protected $guarded = [];

    public function formacoes()
    {
        return $this->belongsToMany('App\Formacao', 'prestador_formacao')->withPivot('id')->wherePivot('ativo', true);
    }

    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }

    public function ordemservicoPrestadores()
    {
        return $this->hasMany('App\OrdemservicoPrestador')->where('ativo', true);
    }

    public function servicos()
    {
        return $this->belongsToMany('App\Servico', 'prestador_servico')->withPivot('id')->wherePivot('ativo', true);
    }
}
