<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestador extends Model
{
    protected $table = 'prestadores';
    protected $guarded = [];

    public function formacoes()
    {
        return $this->belongsToMany('App\Formacao', 'prestador_formacao');
    }

    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }

    public function atribuicoes()
    {
        return $this->hasMany('App\Atribuicao');
    }
}
