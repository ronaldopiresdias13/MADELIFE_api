<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ordemservico extends Model
{
    protected $guarded = [];

    public function orcamento()
    {
        return $this->belongsTo('App\Orcamento');
    }

    public function responsavel()
    {
        return $this->belongsTo('App\Responsavel');
    }

    public function profissional()
    {
        return $this->belongsTo('App\Profissional');
    }

    public function transcricoes()
    {
        return $this->hasMany('App\Transcricao')->where('ativo', true);
    }

    public function escalas()
    {
        return $this->hasMany('App\Escala')->where('ativo', true);
    }

    public function servicos()
    {
        return $this->belongsToMany('App\Servico', 'ordemservico_servico')
            ->withPivot(
                'id',
                'descricao',
                'valordiurno',
                'valornoturno'
            )->wherePivot('ativo', true);
    }

    public function prestadores()
    {
        return $this->belongsToMany('App\Prestador', 'ordemservico_prestador')
            ->wherePivot('ativo', true);
    }
}
