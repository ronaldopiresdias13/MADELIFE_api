<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transcricao extends Model
{
    protected $table = 'transcricoes';
    protected $guarded = [];

    public function ordemservico()
    {
        return $this->belongsTo('App\Ordemservico');
    }

    public function produtos()
    {
        return $this->belongsToMany('App\Produto', 'transcricao_produto')
            ->withpivot(
                "id",
                "quantidade",
                "apresentacao",
                "via",
                "frequencia",
                "tempo",
                "observacao"
            );
    }
    public function itensTranscricao()
    {
        return $this->hasMany('App\TranscricaoProduto');
    }

    public function acoesTrascricao(){
        return $this->hasMany('App\Horariomedicamento');
    }
}
