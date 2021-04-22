<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    protected $table = 'requisicoes';
    protected $guarded = [];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'requisicao_produto')
            ->withPivot('id', 'quantidade', 'observacao', 'status')
            ->wherePivot('ativo', true);
    }
}
