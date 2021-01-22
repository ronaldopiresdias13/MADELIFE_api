<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    public function transcricao_produto()
    {
        return $this->hasOne(TranscricaoProduto::class)->where('ativo', true);
    }
}
