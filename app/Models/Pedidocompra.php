<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedidocompra extends Model
{
    protected $guarded = [];
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedidocompra_produto')
            ->withPivot('id', 'quantidade', 'observacao', 'status');
    }
}
