<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    public function transcricao_produto()
    {
        return $this->hasOne('App\TranscricaoProduto')->where('ativo', true);
    }
}
