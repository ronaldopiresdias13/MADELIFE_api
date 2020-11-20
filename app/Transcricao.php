<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transcricao extends Model
{
    protected $table = 'transcricoes';
    protected $guarded = [];

    public function ordemservico()
    {
        return $this->belongsTo(Ordemservico::class);
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'transcricao_produto')
            ->withpivot(
                "id",
                "quantidade",
                "apresentacao",
                "via",
                "frequencia",
                "tempo",
                "observacao"
            )->wherePivot('ativo', true);
    }
    public function itensTranscricao()
    {
        return $this->hasMany(TranscricaoProduto::class)->where('ativo', true);
    }

    public function acoesTrascricao()
    {
        return $this->hasMany(Horariomedicamento::class)->where('ativo', true);
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }
}
