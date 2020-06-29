<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    protected $table = 'requisicoes';
    protected $guarded = [];

    public function produtos()
    {
        return $this->belongsToMany('App\Produto', 'requisicao_produto')
            ->withPivot('quantidade', 'observacao', 'status');
    }
}
