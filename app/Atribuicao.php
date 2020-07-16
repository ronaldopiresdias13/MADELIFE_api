<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atribuicao extends Model
{
    protected $table = 'atribuicoes';
    protected $guarded = [];

    public function prestador()
    {
        return $this->belongsTo('App\Prestador');
    }
}
