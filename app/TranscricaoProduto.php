<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranscricaoProduto extends Model
{
    protected $table = 'transcricao_produto';
    protected $guarded = [];

    public function horariomedicamentos()
    {
        return $this->hasMany('App\Horariomedicamento');
    }
}
