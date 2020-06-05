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
            'data'      ,
            'hora'      ,
            'status'    
        );
    }

    public function ordemservico()
    {
        return $this->belongsTo('App\Ordemservico');
    }

    public function prestador()
    {
        return $this->belongsTo('App\Prestador', 'prestador_id');
    }
}
