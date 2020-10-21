<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escala extends Model
{
    protected $guarded = [];

    public function cuidados()
    {
        return $this->belongsToMany('App\Cuidado', 'cuidado_escalas')
            ->withPivot(
                'id',
                'data',
                'hora',
                'status'
            )->wherePivot('ativo', true);
    }

    public function pontos()
    {
        return $this->hasMany('App\Ponto')->where('ativo', true);
    }

    public function relatorios()
    {
        return $this->hasMany('App\Relatorio')->where('ativo', true);
    }

    public function monitoramentos()
    {
        return $this->hasMany('App\Monitoramentoescala')->where('ativo', true);
    }

    public function ordemservico()
    {
        return $this->belongsTo('App\Ordemservico');
    }

    public function prestador()
    {
        return $this->belongsTo('App\Prestador', 'prestador_id');
    }

    public function servico()
    {
        return $this->belongsTo('App\Servico', 'servico_id');
    }
    public function acaoMedicamentos()
    {
        return $this->hasMany('App\Acaomedicamento')->where('ativo', true);
    }
}
