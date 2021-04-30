<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranscricaoProduto extends Model
{
    protected $table = 'transcricao_produto';
    protected $guarded = [];

    public function horariomedicamentos()
    {
        return $this->hasMany(Horariomedicamento::class)->where('ativo', true);
    }
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
    public function acoesmedicamentos()
    {
        return $this->hasMany(Acaomedicamento::class)->where('ativo', true);
    }

    public function ocorrencias()
    {
        return $this->hasMany(Ocorrencia::class,'transcricao_produto_id','id');
    }
}
