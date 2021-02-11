<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdemservicoServico extends Model
{
    protected $table = 'ordemservico_servico';
    protected $guarded = [];

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    public function ordemservico()
    {
        return $this->belongsTo(Ordemservico::class);
    }
}
