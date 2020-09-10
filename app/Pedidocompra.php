<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedidocompra extends Model
{
    protected $guarded = [];
    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }
    public function produtos()
    {
        return $this->belongsToMany('App\Produto', 'pedidocompra_produto')
            ->withPivot('id', 'quantidade', 'observacao', 'status');
    }
}
