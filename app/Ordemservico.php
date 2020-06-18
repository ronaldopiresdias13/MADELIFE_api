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

    public function transcricoes()
    {
        return $this->hasMany('App\Transcricao');
    }
}
