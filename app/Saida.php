<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Saida extends Model
{
    use Uuid;

    protected $keyType = 'string';
    public $incrementing = false;
    // protected $primaryKey = 'uuid';
    protected $guarded = [];
    public function produtos()
    {
        return $this->belongsToMany('App\Produto', 'saida_produto')
            ->withPivot('id', 'quantidade', 'valor', 'lote')
            ->wherePivot('ativo', true);
    }
}
