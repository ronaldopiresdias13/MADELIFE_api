<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranscricaoProduto extends Model
{
    protected $table = 'transcricao_produto';
    protected $guarded = [];

    public function horariomedicamentos()
    {
        return $this->hasMany('App\Horariomedicamento')->where('ativo', true);
    }
    public function produto()
    {
        return $this->belongsTo('App\Produto');
    }
    public function acoesmedicamentos()
    {
        return $this->hasMany('App\Acaomedicamento')->where('ativo', true);
    }
}
