<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    protected $table = 'requisicoes';
    protected $guarded = [];

    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }
    public function produtos()
    {
        return $this->belongsToMany('App\Produto', 'requisicao_produto')
            ->withPivot('id', 'quantidade', 'observacao', 'status')
            ->wherePivot('ativo', true);
    }
}
