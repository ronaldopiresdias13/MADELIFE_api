<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotacao extends Model
{
    protected $table = 'cotacoes';
    protected $guarded = [];

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }
    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'cotacao_produto')
            ->withPivot(
                'id',
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
            )->wherePivot('ativo', true);
    }
}
