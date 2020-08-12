<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use Uuid;

    // protected $keyType = 'string';
    // protected $primaryKey = 'uuid';
    protected $guarded = [];

    public function produtos()
    {
        return $this->belongsToMany('App\Produto', 'entrada_produto')
            // ->withPivot('id', 'quantidade', 'observacao', 'status')
            ->wherePivot('ativo', true);
    }
}
