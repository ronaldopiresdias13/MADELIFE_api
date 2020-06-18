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
            );
    }

    public function pontos()
    {
        return $this->hasMany('App\Ponto');
    }

    public function monitoramentos()
    {
        return $this->hasMany('App\Monitoramentoescala');
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
}
