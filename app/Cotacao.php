<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotacao extends Model
{
    protected $table = 'cotacoes';
    protected $guarded = [];

    public function produtos()
    {
        return $this->belongsToMany('App\Produto', 'cotacao_produto')
            ->withPivot(
                'produto_id',
                'fornecedor_id',
                'unidademedida',
                'quantidade',
                'quantidadeembalagem',
                'quantidadetotal',
                'valorunitario',
                'valortotal',
                'formapagamento',
                'prazoentrega',
                'observacao',
                'situacao'
            );
    }
}
